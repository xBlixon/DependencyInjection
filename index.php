<?php
require "vendor/autoload.php";

use Velsym\DependencyInjection\DependencyManager;

$dependencies = [
//    Example of how to use dependencies
//    (require "testing/dependencies.php"),
//    (require "testing/ext-dependencies.php"),
//    (require "testing/ext-dependencies-2.php")
];

DependencyManager::loadDependencies(array_merge(...$dependencies));

$person = DependencyManager::resolveClassToInstance("bogus");
var_dump($person);