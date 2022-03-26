<?php

namespace Boot\Database;

use App\Config\Config;
use Boot\application;
use Boot\Src\singleton;
use Exception;
use PDO;
use PDOStatement;

class DB extends singleton
{
    /**
     * @param string $hostname Can be either a host name or an IP address. Passing the NULL value or the string "localhost" to this parameter, the local host is assumed. When possible, pipes will be used instead of the TCP/IP protocol.
     */
    private $hostname;

    /**
     * @param string $username The MySQL user name.
     */
    private ?string $username;

    /**
     * @param string $password The MySQL password.
     */
    private ?string $password;

    /**
     * @param string $database Database to be used when performing queries.
     */
    private $database;

    /**
     * @var PDO Object represents db connection.
     */
    private PDO $connection;

    /**
     * @var string Credential for pdo object.
     */
    private string $dsn;

    protected function __construct()
    {
        parent::__construct();

        $config = Config::database();

        $this->hostname = $config['db_host'];
        $this->database = $config['db_database'];
        $this->username = $config['db_username'];
        $this->password = $config['db_password'];
        $this->dsn = $this->dataSourceName();

    }

    public function query(string $query, array $bindings = []): PDOStatement
    {
        try {
            $pdo = $this->getConnection();

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare($query);

            $stmt->execute($bindings);
        } catch (Exception $e) {
            application::log($e->getMessage());
            die($e->getMessage());
        }

        return $stmt;
    }

    /**
     * @return PDO
     * @throws Exception
     */
    protected function getConnection(): PDO
    {
        if (!$this->connection) {
            $this->connection = $this->makeConnection();
        }
        return $this->connection;
    }

    private function dataSourceName(): string
    {
        return 'mysql:dbname=' . $this->database . ';host=' . $this->hostname;
    }

    /**
     * Open a new connection to the MySQL server
     * @return PDO
     * @throws Exception
     */
    private function makeConnection(): PDO
    {
        $connection = new PDO($this->dsn, $this->username, $this->password);

        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $connection;
    }
}