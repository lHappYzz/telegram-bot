<?php


namespace Boot\Src;


abstract class singleton {

    protected function __construct() {
        //
    }

    /**
     * @return static
     */
    final public static function getInstance() {
        static $aoInstance = array();

        $calledClassName = get_called_class();

        if (! isset ($aoInstance[$calledClassName])) {
            $aoInstance[$calledClassName] = new $calledClassName();
        }

        return $aoInstance[$calledClassName];
    }

    final private function __clone() {
        //
    }

    final private function __wakeup() {
        //
    }
}