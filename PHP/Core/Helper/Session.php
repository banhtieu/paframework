<?php

namespace Core\Helper;

/**
 * start the session
 */
session_start();

/**
 * Class Session
 * @package Core\Helper
 */
class Session {


    /**
     * @param string $key get the session variable
     *
     * @return mixed the value that stored in the session
     */
    public static function get($key) {
        $result = null;

        if (isset($_SESSION[$key])) {
            $result = $_SESSION[$key];
        }

        return $result;
    }


    /**
     * @param string $key the session variable
     * @param mixed $value the value of the object to set
     */
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key key to remove
     */
    public static function remove($key) {
        unset($_SESSION[$key]);
    }

}