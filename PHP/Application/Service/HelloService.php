<?php


namespace Application\Service;


/**
 * Class HelloService
 * Say Hello to neighbors
 * @package Application\Service
 */
class HelloService {


    /**
     * Start by saying hello
     *
     * @param string $name name of the user
     * @return string message to that user
     */
    public function sayHello($name) {
        return "Hello $name! You have a beautiful name!";
    }
}