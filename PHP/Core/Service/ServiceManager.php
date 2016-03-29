<?php
/**
 * Created by PhpStorm.
 * User: banhtieu
 * Date: 3/23/2016
 * Time: 4:17 PM
 */

namespace Core\Service;

class ServiceManager {

    /**
     * Process the request
     */
    public function processRequest() {

        if (isset($_SERVER["PATH_INFO"])){
            $path = $_SERVER["PATH_INFO"];

            $components = explode("/", $path);
            $serviceName = $components[1];
            $method = $components[2];

            if ($serviceName && $method) {
                $container = new Container();
                $serviceClass = "Application\\Service\\$serviceName";
                $service = $container->resolve($serviceClass);
                $postBody = file_get_contents("php://input");

                if (isset($_FILES["file"])) {
                    $params = array(
                        new UploadedFile("file")
                    );
                } else if (strlen($postBody)) {
                    $params = json_decode($postBody);

                    if (is_object($params)) {
                        $params = array($params);
                    }
                } else {
                    $params = array();
                }

                $result = call_user_func_array(array($service, $method), $params);
                header("Content-Type: application/json");
                echo json_encode($result);

            } else {
                throw new \Exception("Invalid request!!");
            }

        } else {
            throw new \Exception("Invalid request!!");
        }
    }
}