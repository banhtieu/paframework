<?php
/**
 * Created by PhpStorm.
 * User.php: banh.tieu
 * Date: 3/23/2016
 * Time: 9:18 AM
 */

require_once dirname(__FILE__) . "/../Autoload.php";

use Core\Migration;

/**
 * use to migrate database to latest
 */
function main() {
    $migration = new Migration();
    $migration->execute();
}

main();