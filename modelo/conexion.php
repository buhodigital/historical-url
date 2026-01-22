<?php 
require_once __DIR__ . '/../config/config.php';

abstract class Conexion{
    protected $pdo;

    public function __construct(){
        $this->open_connection();
    }

    private function open_connection(){
        try {
            $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET;
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            error_log("Connection failed: " . $e->getMessage());
            die("Error de conexión a la base de datos.");
        }
    }

    protected function query($sql, $params = []){
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Query failed: " . $e->getMessage());
            die("Error al procesar la solicitud.");
        }
    }
}
?>