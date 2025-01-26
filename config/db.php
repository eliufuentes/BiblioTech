<?php

class ConexionDB {
    private static $conexion = null;

    public static function getConexion() {
        if (self::$conexion === null) {
            try {
                self::$conexion = new PDO('mysql:host=localhost;dbname=biblioteca', 'root', '');
                self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$conexion;
    }
}
