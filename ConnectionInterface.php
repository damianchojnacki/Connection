<?php

namespace Connection;

interface ConnectionInterface {
    public function get(Fetch $fetch = null);
    public function prepare($column, $value = null);
    public function escape($value);
}
