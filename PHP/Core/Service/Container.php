<?php

namespace Core\Service;
use Application\Config\ApplicationConfig;
use Core\Database\Collection;


/**
 * The service manager that manage all service
 * and inject dependencies
 *
 * Class Container
 * @package Core\Service
 */
class Container {

    /**
     * create an instance of a class
     */
    public function construct() {

    }

    /**
     * Resolve a class in container
     *
     * @param $className string name of the class
     * @param $parameterName string name of the parameter
     *
     * @return mixed the object
     */
    public function resolve($className, $parameterName = null) {
        $result = null;

        // resolve the collection
        if ($className == "Core\\Database\\Collection") {
            $result = new Collection(str_replace("Collection", "", $parameterName));
        } else if (strpos($className, "Application\\Service\\") === 0){
            $result = $this->instantiate($className);
        } else {
            $dependenciesConfiguration = ApplicationConfig::configDependencies();

            if (isset($dependenciesConfiguration[$className])) {
                $className = $dependenciesConfiguration[$className];
                $result = $this->instantiate($className);
            }
        }

        return $result;
    }

    /**
     * Create an instance of a class with name
     * @param $className string name of the class
     *
     * @return mixed the object
     */
    private function instantiate($className) {
        $result = null;

        $reflector = new \ReflectionClass($className);
        $constructor = $reflector->getConstructor();

        if ($constructor != null) {
            $parameterDefinitions = $constructor->getParameters();
            $parameters = array();

            foreach ($parameterDefinitions as $definition) {
                $type = $definition->getClass()->name;
                $parameters[] = $this->resolve($type, $definition->getName());
            }

            $result = $reflector->newInstanceArgs($parameters);
        } else {
            $result = $reflector->newInstanceWithoutConstructor();
        }

        return $result;

    }



}


?>