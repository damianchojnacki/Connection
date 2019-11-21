<?php


class FetchArray extends Fetch{
    public function __invoke($response, $classname) {
        list($childClass, $caller) = debug_backtrace(false, 2);

        switch($caller['class']){
            case 'ConnectionPDO':
                return $response->fetchAll(PDO::FETCH_ASSOC);
            break;

            case 'ConnectionMysqli':
                return $response->fetch_array();
                break;
        }
        return true;
    }
}