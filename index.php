<?php
require_once 'classes/Libro.php';
require_once 'classes/Biblioteca.php';

$biblioteca = new Biblioteca();
$mensaje = "";

// Parámetros iniciales
$filtro = $_GET['filtro'] ?? 'todos';
$pagina = $_GET['pagina'] ?? 1;
$limite = $_GET['limite'] ?? 5;
$busqueda = $_GET['busqueda'] ?? '';
$inicio = ($pagina - 1) * $limite;

// Manejar acciones
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['agregar'])) {
        $titulo = $_POST['titulo'];
        $autor = $_POST['autor'];
        $categoria = $_POST['categoria'];
        $nuevoLibro = new Libro($titulo, $autor, $categoria);
        $biblioteca->agregarLibro($nuevoLibro);
        $mensaje = "¡Libro agregado con éxito!";
    } elseif (isset($_POST['editar'])) {
        $id = $_POST['id'];
        $titulo = $_POST['titulo'];
        $autor = $_POST['autor'];
        $categoria = $_POST['categoria'];
        $estado = $_POST['estado'];
        $biblioteca->editarLibro($id, $titulo, $autor, $categoria, $estado);
        $mensaje = "¡Libro actualizado con éxito!";
    } elseif (isset($_POST['eliminar'])) {
        $id = $_POST['id'];
        $biblioteca->eliminarLibro($id);
        $mensaje = "¡Libro eliminado!";
    } elseif (isset($_POST['cambiarEstado'])) {
        $id = $_POST['id'];
        $estadoActual = $_POST['estadoActual'];
        $nuevoEstado = $estadoActual == 1 ? 0 : 1; // Cambiar estado
        $libro = $biblioteca->listarLibros('todos', 0, 1, '')[0];
        $biblioteca->editarLibro($id, $libro->getTitulo(), $libro->getAutor(), $libro->getCategoria(), $nuevoEstado);
        $mensaje = "¡Estado del libro cambiado con éxito!";
    }
}

// Obtener libros
$libros = $biblioteca->listarLibros($filtro, $inicio, $limite, $busqueda);
$totalLibros = $biblioteca->contarLibros('todos', $busqueda);
$totalPaginas = ceil($totalLibros / $limite);

// Estadísticas
$totalDisponibles = $biblioteca->contarLibros('disponibles');
$totalPrestados = $biblioteca->contarLibros('prestados');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center mb-4">Sistema de Gestión de Biblioteca</h1>

        <?php if ($mensaje): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($mensaje) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Formulario de búsqueda -->
        <form method="GET" action="" class="mb-4 d-flex">
            <input type="text" name="busqueda" class="form-control me-2" placeholder="Buscar por título, autor o categoría" value="<?= htmlspecialchars($busqueda) ?>">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>

        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-header">Total de Libros</div>
                    <div class="card-body">
                        <h4 class="card-title"><?= $totalLibros ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-header">Libros Disponibles</div>
                    <div class="card-body">
                        <h4 class="card-title"><?= $totalDisponibles ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-danger">
                    <div class="card-header">Libros Prestados</div>
                    <div class="card-body">
                        <h4 class="card-title"><?= $totalPrestados ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de libros -->
        <div class="card">
            <div class="card-header bg-secondary text-white">Lista de Libros</div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Categoría</th>
                            <th>Disponibilidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($libros as $libro): ?>
                            <tr>
                                <td><?= htmlspecialchars($libro->getTitulo()) ?></td>
                                <td><?= htmlspecialchars($libro->getAutor()) ?></td>
                                <td><?= htmlspecialchars($libro->getCategoria()) ?></td>
                                <td>
                                    <form method="POST" action="" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $libro->getId() ?>">
                                        <input type="hidden" name="estadoActual" value="<?= $libro->estaDisponible() ? 1 : 0 ?>">
                                        <button type="submit" name="cambiarEstado" class="btn btn-sm <?= $libro->estaDisponible() ? 'btn-success' : 'btn-danger' ?>">
                                            <?= $libro->estaDisponible() ? 'Disponible' : 'Prestado' ?>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editarModal<?= $libro->getId() ?>">Editar</button>
                                    <form method="POST" action="" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $libro->getId() ?>">
                                        <button type="submit" name="eliminar" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Paginación -->
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                            <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                                <a class="page-link" href="?filtro=<?= $filtro ?>&limite=<?= $limite ?>&pagina=<?= $i ?>&busqueda=<?= htmlspecialchars($busqueda) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
