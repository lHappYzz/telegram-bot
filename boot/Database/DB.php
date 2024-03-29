<?php

namespace Boot\Database;

use Boot\application;
use Boot\Src\singleton;
use Exception;
use mysqli;
use mysqli_result;

class DB extends singleton
{
    /**
     * @param string $hostname Can be either a host name or an IP address. Passing the NULL value or the string "localhost" to this parameter, the local host is assumed. When possible, pipes will be used instead of the TCP/IP protocol.
     */
    private $hostname;

    /**
     * @param string $username The MySQL user name.
     */
    private $username;

    /**
     * @param string $password If not provided or NULL, the MySQL server will attempt to authenticate the user against those user records which have no password only.
     */
    private $password;

    /**
     * @param string $database If provided will specify the default database to be used when performing queries.
     */
    private $database;

    private $connection;

    protected function __construct() {
        parent::__construct();

        $config = application::$config;
        $this->hostname = $config['db_host'];
        $this->username = $config['db_username'];
        $this->password = $config['db_password'];
        $this->database = $config['db_database'];

    }

    /**
     * Open a new connection to the MySQL server
     * @return mysqli
     * @throws Exception
     */
    private function makeConnection() {
        $dbconnection = mysqli_connect($this->hostname, $this->username, $this->password, $this->database);
        if ($dbconnection === false) {
            throw new Exception('Error while connecting to the database.');
        }
        return $dbconnection;
    }

    private function getConnection() {
        if (!$this->connection) {
            try {
                $this->connection = $this->makeConnection();
            } catch (Exception $e) {
                application::log($e->getMessage());
                die($e->getMessage());
            }
        }
        return $this->connection;
    }

    /**
     * @param $sql
     * SQL string
     * @return mysqli_result|true
     * On success mysqli_result or true returned
     */
    public function query($sql) {
        try {
            $result = $this->getConnection()->query($sql);
            if ($result === false) {
                throw new Exception('The query ended with error. ' . mysqli_error($this->getConnection()));
            }
        } catch (Exception $e) {
            application::log($e->getMessage());
            die($e->getMessage());
        }
        return $result;
    }

}