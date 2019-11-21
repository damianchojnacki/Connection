<?php

namespace Connection;

use PDO;

class FetchObject extends Fetch{
    public function __invoke($response, $classname) {
        list($childClass, $caller) = debug_backtrace(false, 2);

        switch($caller['class']){
            case 'Connection\ConnectionPDO':
                return $response->fetchAll(PDO::FETCH_OBJ);
            break;

            case 'Connection\ConnectionMysqli':
                return $response->fetch_object();
                break;
        }
        return true;
    }
}