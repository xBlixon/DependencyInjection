<?php
require "vendor/autoload.php";

use Velsym\DependencyInjection\DependencyManager;
use Velsym\TestClasses\Name;
use Velsym\TestClasses\Person;
use Velsym\TestClasses\Surname;

$dependencies = [
//    Example of how to use dependencies
//    (require "testing/dependencies.php"),
//    (require "testing/ext-dependencies.php"),
//    (require "testing/ext-dependencies-2.php")
];

DependencyManager::loadDependencies(array_merge(...$dependencies));

$person = new Person(new Name("gustavo"), new Surname("fring"));

$v = DependencyManager::callMethodWithResolvedArguments($person, 'presentYourself', ['age' => 21]);

var_dump($v);