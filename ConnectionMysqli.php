<?php

namespace Connection;

use mysqli;
use mysqli_sql_exception;

require_once 'ConnectionTrait.php';

//Simple mysqli wrapper coded by Damian Chojnacki
//Example usage:
//$connection = new ConnectionMysqli('localhost', 'root', 'test_db'); <- establish new connection
//$connection->table('test_table')->get(); <- returns all records from test_table
//$connection->table('test_table')->select('example_column')->get(); <- returns example_column's from test_table

class ConnectionMysqli extends mysqli implements ConnectionInterface {

    use ConnectionTrait;

    public function __construct($host, $username, $database, $password = ''){
        parent::__construct($host, $username, $password, $database);
        $this->set_charset('utf8mb4');

        try{
            if ($this->connect_errno != 0) throw new mysqli_sql_exception($this->connect_error);
        } catch(mysqli_sql_exception $e){
            echo $e->getMessage();
            exit();
        }
    }

    public function escapeAll($column, $value = null) {
        if (is_array($column)) {
            $where = [];

            foreach ($column as $key => $value) {
                $where[$key] = '`' . $key . '` = "' . $this->escape($value) . '"';
            }

        } else if (is_array($value)) {
            $where = [];

            foreach ($value as $key => $val)
                $where[$key] = $this->escape($val);

            $where = '"' . implode('", "', $where) . '"';
        } else {
            $where = '"' . $this->escape($value) . '"';
        }

        return $where;
    }

    public function escape($value) {
        return $this->real_escape_string($value);
    }

    public function get(Fetch $fetch = null) {
        $this->query = $this->select . $this->from . $this->where . $this->group . $this->order;

        is_null($fetch) && $fetch = new $this->fetchDefault;

        $result = [];

        if ($response = $this->query($this->query)) {
            while ($obj = $fetch($response, get_class($this)))
                $result[] = $obj;
        }

        $this->select = 'SELECT *';
        $this->where = null;
        $this->group = null;

        return $result;
    }
}