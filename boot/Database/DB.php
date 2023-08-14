<?php

namespace Boot\Database;

use App\Config\Config;
use Boot\Log\Logger;
use Boot\Src\Abstracts\Singleton;
use PDO;
use PDOStatement;
use Throwable;

class DB extends Singleton
{
    /**
     * @param string $hostname Can be either a host name or an IP address. Passing the NULL value or the string "localhost" to this parameter, the local host is assumed. When possible, pipes will be used instead of the TCP/IP protocol.
     */
    private string $hostname;

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
    private string $database;

    /**
     * @var PDO Object represents db connection.
     */
    private $connection;

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

    /**
     * @param string $query
     * @param array $bindings
     * @return PDOStatement
     */
    public function query(string $query, array $bindings = []): PDOStatement
    {
        $pdo = $this->getConnection();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare($query);

        try {
            $stmt->execute($bindings);
        } catch (Throwable $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
            Logger::logError(print_r($stmt->errorInfo(), true));
        }

        return $stmt;
    }

    /**
     * @return PDO
     */
    protected function getConnection(): PDO
    {
        if (!$this->connection) {
            $this->connection = $this->makeConnection();
        }
        return $this->connection;
    }

    /**
     * @return string
     */
    private function dataSourceName(): string
    {
        return 'mysql:dbname=' . $this->database . ';host=' . $this->hostname;
    }

    /**
     * Open a new connection to the MySQL server
     * @return PDO
     */
    private function makeConnection(): PDO
    {
        $connection = new PDO($this->dsn, $this->username, $this->password);

        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $connection;
    }
}