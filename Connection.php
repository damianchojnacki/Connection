<?php

namespace Connection;

require_once 'Fetch.php';
require_once 'FetchObject.php';
require_once 'FetchArray.php';
require_once 'ConnectionHelper.php';

abstract class Connection {
    protected $query = null, $select = 'SELECT *', $from, $where = null, $group = null, $order = null, $fetchDefault = FetchObject::class, $wrapper;

    abstract protected function get(Fetch $fetch = null);
    abstract protected function prepare($column, $value = null);
    abstract protected function escape($value);

    public function table($table){
        $this->from = ' FROM `' . $table . '`';

        return $this;
    }

    public function select($select) {
        if (is_array($select))
            $select = implode("`,`", $select);

        $this->select = 'SELECT `' . $select . '`';

        return $this;
    }

    public function where($column, $value = null, $join = 'AND',  $operator= 'OR') {
        if (empty($this->where)) $join = 'WHERE';

        $where = $this->prepare($column, $value);

        if (is_array($column)) {
            $where = implode(' ' . $operator . ' ', $where);
            $this->where .= ' ' . $join . ' (' . $where . ')';
        } else if (is_array($value))
            $this->where .= ' ' . $join . ' `' . $column . '` IN (' . $where . ')';
        else
            $this->where .= ' ' . $join . ' `' . $column . '`= ' . $where;

        return $this;
    }

    public function like($column, $exp, $operator = 'AND') {
        $where = $this->prepare($column, $exp);

        empty($this->where) && $operator = 'WHERE';

        $this->where .= ' ' . $operator . ' `' . $column . '` LIKE ' . $where;

        return $this;
    }

    public function groupBy($column) {
        $this->group = ' GROUP BY `' . $column . '`';

        return $this;
    }

    public function orderBy($column, $sortDirection = 'DESC') {
        $this->order = ' ORDER BY `' . $column . '` ' . $sortDirection;

        return $this;
    }

    public function first() {
        return $this->get()[0];
    }
}
