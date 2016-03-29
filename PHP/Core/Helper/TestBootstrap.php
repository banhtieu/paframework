<?php
/**
 * Created by PhpStorm.
 * User.php: banht_000
 * Date: 5/31/2015
 *
 */

use Core\Migration;

require_once __FILE__ . "/../../Autoload.php";

define('TESTING', true);

// do a migration
$migration = new Migration();
$migration->execute(true);
unset($migration);

