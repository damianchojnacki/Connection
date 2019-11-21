<?php

namespace Connection;

interface ConnectionInterface {
    public function get(Fetch $fetch = null);
    public function escapeAll($column, $value = null);
    public function escape($value);
}