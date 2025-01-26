<?php
require_once 'config/db.php';

try {
    $conexion = ConexionDB::getConexion();

    // Verificar si ya hay datos en la tabla
    $stmt = $conexion->query("SELECT COUNT(*) FROM libros");
    $cantidad = $stmt->fetchColumn();

    if ($cantidad > 0) {
        die("La base de datos ya contiene datos. No se realizó ninguna acción.");
    }

    // Insertar datos iniciales (como en el script anterior)
    $libros = [
        ['titulo' => 'Cien Años de Soledad', 'autor' => 'Gabriel García Márquez', 'categoria' => 'Novela', 'disponible' => 1],
        ['titulo' => 'Don Quijote de la Mancha', 'autor' => 'Miguel de Cervantes', 'categoria' => 'Clásico', 'disponible' => 1],
        ['titulo' => '1984', 'autor' => 'George Orwell', 'categoria' => 'Ficción', 'disponible' => 1],
        ['titulo' => 'El Principito', 'autor' => 'Antoine de Saint-Exupéry', 'categoria' => 'Infantil', 'disponible' => 1],
        ['titulo' => 'Crónica de una Muerte Anunciada', 'autor' => 'Gabriel García Márquez', 'categoria' => 'Novela', 'disponible' => 1],
        ['titulo' => 'Rayuela', 'autor' => 'Julio Cortázar', 'categoria' => 'Ficción', 'disponible' => 1],
        ['titulo' => 'El Aleph', 'autor' => 'Jorge Luis Borges', 'categoria' => 'Cuento', 'disponible' => 1],
    ];

    $query = "INSERT INTO libros (titulo, autor, categoria, disponible) VALUES (:titulo, :autor, :categoria, :disponible)";
    $stmt = $conexion->prepare($query);

    foreach ($libros as $libro) {
        $stmt->execute([
            'titulo' => $libro['titulo'],
            'autor' => $libro['autor'],
            'categoria' => $libro['categoria'],
            'disponible' => $libro['disponible']
        ]);
    }

    echo "Datos iniciales insertados con éxito.";
} catch (PDOException $e) {
    die("Error al insertar datos: " . $e->getMessage());
}
