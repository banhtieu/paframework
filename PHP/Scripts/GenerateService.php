<?php

require_once dirname(__FILE__) . "/../Autoload.php";


/**
 * Class ServiceGenerator - class that generate typescript
 * classes
 */
class ServiceGenerator {

    /**
     * path to model folder
     */
    const ModelFolder = "PHP/Application/Model";

    /**
     * path to service folder
     */
    const ServiceFolder = "PHP/Application/Service";

    /**
     * path to typescript folder
     */
    const TypeScriptFile = "Scripts/services.ts";

    /**
     * @var resource output file
     */
    private $output;

    /**
     * generate models and services
     */
    public function execute() {

        $this->output = fopen(self::TypeScriptFile, "w");
        $this->generateModels();
        $this->generateServices();

        fclose($this->output);
    }


    /**
     * generate models
     */
    public function generateModels() {
        $file = opendir(self::ModelFolder);

        // there is fileName
        while (($fileName = readdir($file)) !== false) {
            if (substr($fileName, -4) == ".php") {
                $this->generateModel($fileName);
            }
        }
    }

    /**
     * Generate a model
     * @param $fileName
     */
    public function generateModel($fileName) {
        include_once self::ModelFolder . "/" . $fileName;
        $className = basename($fileName, ".php");
        $fullName = "Application\\Model\\$className";

        $reflector = new ReflectionClass($fullName);

        fwrite($this->output, "/** generated model for $className */\n");
        fwrite($this->output, "class $className {\n");

        // for
        foreach ($reflector->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $type = $this->getType($property);
            $name = $property->getName();

            fwrite($this->output, "\n    $name: $type\n");
        }

        fwrite($this->output, "}\n\n");

    }

    /**
     * @param $property ReflectionProperty get type of the property
     *
     * @return string type name
     */
    public function getType(ReflectionProperty $property) {
        $docComment = $property->getDocComment();
        $type = "any";

        if (preg_match("/@var\\s+([^\\s]+)\\s/", $docComment, $match)) {
            $phpType = $match[1];

            switch ($phpType) {
                case "string":
                case "boolean":
                    $type = $phpType;
                    break;
                case "int":
                case "float":
                case "double":
                    $type = "number";
                    break;
            }
        }

        return $type;
    }


    /**
     * generate models
     */
    public function generateServices() {
        $file = opendir(self::ServiceFolder);

        // there is fileName
        while (($fileName = readdir($file)) !== false) {
            if (substr($fileName, -4) == ".php") {
                $this->generateService($fileName);
            }
        }
    }


    /**
     * Generate a model
     * @param $fileName
     */
    public function generateService($fileName) {
        include_once self::ServiceFolder . "/" . $fileName;
        $className = basename($fileName, ".php");
        $serviceName = strtolower(substr($className, 0, 1)) . substr($className, 1);
        $fullName = "Application\\Service\\$className";

        $reflector = new ReflectionClass($fullName);

        fwrite($this->output, "/** generated service for $className */\n");
        fwrite($this->output, "@service(\"$serviceName\")\n");
        fwrite($this->output, "class $className extends RemoteService {\n");

        // for
        foreach ($reflector->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {

            if (!$method->isConstructor() && !$method->isDestructor()) {

                $methodName = $method->getName();

                $parameters = array();

                foreach ($method->getParameters() as $parameter) {
                    $parameters[] = $parameter->getName();
                }

                $uploadMethod = sizeof($parameters) == 1
                        && $method->getParameters()[0]->getClass() != null
                        && is_object($method->getParameters()[0]->getClass())
                        && $method->getParameters()[0]->getClass()->getName() == "Core\\Service\\UploadedFile";


                fwrite($this->output, "\n    " . str_replace("$", "", $method->getDocComment()) . "\n");
                fwrite($this->output, "    $methodName(");

                if ($uploadMethod) {
                    fwrite($this->output, $parameters[0] . ": flowjs.IFlow");
                } else {
                    fwrite($this->output, implode($parameters, ", "));
                }

                fwrite($this->output, ") {\n");

                if ($uploadMethod) {
                    fwrite($this->output, "        return this.uploadFile(\"$className/$methodName\", "
                        . $parameters[0] . ")\n");
                } else {
                    fwrite($this->output, "        return this.execute(\"$className/$methodName\", ["
                        . implode($parameters, ", ") . "]"
                        . ")\n");
                }

                fwrite($this->output, "    }\n");

            }

        }

        fwrite($this->output, "}\n\n");

    }

}

/**
 * the main entry of the script
 */
function main() {
    $generator = new ServiceGenerator();
    $generator->execute();
}

main();