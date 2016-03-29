<?php
/**
 * Created by PhpStorm.
 * User.php: banh.tieu
 * Date: 5/31/2015
 * Time: 4:26 AM
 */

namespace Application\Config;
use Core\Database\Collection;
use Core\Database\DatabaseConfig;
use Core\Service\Container;

/**
 * Class Configuration
 * @package Application\Config
 */
class ApplicationConfig {

    /**
     * @return DatabaseConfig[] the database configuration
     */
    public static function configDatabase() {

        return array(
            'dev' => new DatabaseConfig('mysql:host=127.0.0.1;port=3307;dbname=paframework_dev', 'root', 'root'),
            'test' => new DatabaseConfig('mysql:host=127.0.0.1;dbname=paframework_test', 'root', 'root'),
            'production' => new DatabaseConfig('', '', '')
        );
    }


    /**
     *  configure dependencies injection
     */
    public static function configDependencies() {
        return array(
            "Application\\Validator\\UserValidator" => "Application\\Validator\\UserValidator"
        );
    }
}