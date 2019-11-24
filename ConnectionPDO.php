<?php

namespace Connection;

use PDO;
use PDOException;

require_once 'Connection.php';

//Simple PDO wrapper coded by Damian Chojnacki
//Example usage:
//$connection = new ConnectionPDO('localhost', 'root', 'test_db'); <- establish new connection
//$connection->table('test_table')->get(); <- returns all records from test_table
//$connection->table('test_table')->select('example_column')->get(); <- returns example_column's from test_table

class ConnectionPDO extends Connection {

    private $escaped = [];

    public function __construct($host, $username, $database, $password = '', $charset = 'utf8mb4') {
        $dsn = "mysql:host=$host;dbname=$database;charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->wrapper = new PDO($dsn, $username, $password, $options);
        } catch(PDOException $e){
            echo $e->getMessage();
            exit();
        }
    }

    public function prepare($column, $value = null) {
        if (is_array($column)) {
            $keys = [];
            $where = [];

            foreach ($column as $key => $value) {
                $where[$key] = '`' . $key . '` = :' . $key;
                $keys[':' . $key] = $value;
            }

            $this->escape($keys);
        } else if (is_array($value)) {
            $keys = [];

            foreach ($value as $key => $val)
                $keys[':' . ConnectionHelper::removeWhiteSpace($column) . $key] = $val;

            $where = implode(', ', array_keys($keys));
            $this->escape($value);
        } else {
            $where = ':' . ConnectionHelper::removeWhiteSpace($column);
            $this->escape([':' . ConnectionHelper::removeWhiteSpace($column) => $value]);
        }

        return $where;
    }

    public function escape($value){
        $this->escaped = array_merge($this->escaped, $value);
    }

    public function get(Fetch $fetch = null) {
        $this->query = $this->select . $this->from . $this->where . $this->group . $this->order;

        is_null($fetch) && $fetch = new $this->fetchDefault;

        try {
            $response = $this->wrapper->prepare($this->query);
            $response->execute($this->escaped);
            $result = $fetch($response, get_class($this));
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }

        $this->select = 'SELECT *';
        $this->where = null;
        $this->group = null;
        $this->order = null;

        return $result;
    }
}
