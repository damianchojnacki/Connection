<?php


class ConnectionHelper {
    public static function removeWhiteSpace($string) {
        return str_replace(' ', '', $string);
    }
}