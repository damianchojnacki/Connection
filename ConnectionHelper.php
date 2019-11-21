<?php

namespace Connection;

class ConnectionHelper {
    public static function removeWhiteSpace($string) {
        return str_replace(' ', '', $string);
    }
}