<?php

/**
 * Report everything
 */
error_reporting(E_ALL);

// use Core Namespace
use Core\Service\ServiceManager;

require_once 'PHP/Autoload.php';


/**
 * Main entry of the application
 * @throws Exception exceptions that service produces
 */
function main() {

    // initialize the service container
    $manager = new ServiceManager();
    $manager->processRequest();
}

try {
    main();
}catch (Exception $e){

    // bad request

    http_response_code(400);
    header("Content-Type: application/json");
    
    echo json_encode(array(
        'exception' => true,
        'message' => $e->getMessage()
    ));
}
