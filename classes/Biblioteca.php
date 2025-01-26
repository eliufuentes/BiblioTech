<?php
require_once 'config/db.php';
require_once 'Libro.php';

class Biblioteca {
    private $conexion;

    public function __construct() {
        $this->conexion = ConexionDB::getConexion();
    }

    public function agregarLibro(Libro $libro) {
        $query = "INSERT INTO libros (titulo, autor, categoria, disponible) VALUES (:titulo, :autor, :categoria, :disponible)";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute([
            ':titulo' => $libro->getTitulo(),
            ':autor' => $libro->getAutor(),
            ':categoria' => $libro->getCategoria(),
            ':disponible' => $libro->estaDisponible()
        ]);
    }

    public function listarLibros($filtro = 'todos', $inicio = 0, $limite = 5, $busqueda = '') {
        $query = "SELECT * FROM libros WHERE 1";
        $params = [];

        if ($filtro === 'disponibles') {
            $query .= " AND disponible = 1";
        } elseif ($filtro === 'prestados') {
            $query .= " AND disponible = 0";
        }

        if (!empty($busqueda)) {
            $query .= " AND (titulo LIKE :busqueda OR autor LIKE :busqueda)";
            $params[':busqueda'] = '%' . $busqueda . '%';
        }

        $query .= " LIMIT :inicio, :limite";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $libros = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $libros[] = new Libro($row['titulo'], $row['autor'], $row['categoria'], $row['disponible'], $row['id']);
        }
        return $libros;
    }

    public function contarLibros($filtro = 'todos', $busqueda = '') {
        $query = "SELECT COUNT(*) AS total FROM libros WHERE 1";
        $params = [];

        if ($filtro === 'disponibles') {
            $query .= " AND disponible = 1";
        } elseif ($filtro === 'prestados') {
            $query .= " AND disponible = 0";
        }

        if (!empty($busqueda)) {
            $query .= " AND (titulo LIKE :busqueda OR autor LIKE :busqueda)";
            $params[':busqueda'] = '%' . $busqueda . '%';
        }

        $stmt = $this->conexion->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function prestarLibro($id) {
        $query = "UPDATE libros SET disponible = 0 WHERE id = :id AND disponible = 1";
        $stmt = $this->conexion->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    public function devolverLibro($id) {
        $query = "UPDATE libros SET disponible = 1 WHERE id = :id AND disponible = 0";
        $stmt = $this->conexion->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    public function editarLibro($id, $titulo, $autor, $categoria, $estado) {
        $query = "UPDATE libros SET titulo = :titulo, autor = :autor, categoria = :categoria, disponible = :estado WHERE id = :id";
        $stmt = $this->conexion->prepare($query);
        return $stmt->execute([
            ':id' => $id,
            ':titulo' => $titulo,
            ':autor' => $autor,
            ':categoria' => $categoria,
            ':estado' => $estado
        ]);
    }

    public function eliminarLibro($id) {
        $query = "DELETE FROM libros WHERE id = :id";
        $stmt = $this->conexion->prepare($query);
        return $stmt->execute([':id' => $id]);
    }
}
