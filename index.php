<?php
require "vendor/autoload.php";

use Velsym\DependencyInjection\DependencyManager;

$dependencies = (require "testing/dependencies.php");
DependencyManager::loadDependencies($dependencies);
//$person = DependencyManager::resolveClassToInstance("Velsym\TestClasses\Person");
//var_dump($person);