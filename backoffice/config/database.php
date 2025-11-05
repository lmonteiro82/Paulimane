<?php
/**
 * Configuração da Base de Dados
 * Paulimane Backoffice
 */

// Detectar ambiente automaticamente
$isProduction = (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'localhost') === false);

if ($isProduction) {
    // PRODUÇÃO (PTisp):
    define('DB_HOST', 'localhost');
    define('DB_USER', 'pauliman_admin');
    define('DB_PASS', 'paulimane2000');
    define('DB_NAME', 'pauliman_Site');
    define('DB_CHARSET', 'utf8mb4');
} else {
    // DESENVOLVIMENTO LOCAL (Docker):
    define('DB_HOST', '127.0.0.1');
    define('DB_USER', 'root');
    define('DB_PASS', 'senha123');
    define('DB_NAME', 'Paulimane');
    define('DB_CHARSET', 'utf8mb4');
}

// Classe de conexão à base de dados
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $charset = DB_CHARSET;
    private $conn;
    private $error;

    /**
     * Conectar à base de dados
     */
    public function connect() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            error_log("Database Connection Error: " . $this->error);
            return null;
        }

        return $this->conn;
    }

    /**
     * Obter erro de conexão
     */
    public function getError() {
        return $this->error;
    }
}

/**
 * Função helper para obter conexão
 */
function getDBConnection() {
    $database = new Database();
    return $database->connect();
}
?>
