<?php

$requestUri = $_SERVER["SCRIPT_NAME"];

$target = false;

if (!file_exists(".$requestUri")) {
    $target = "index.html";
}

return $target;