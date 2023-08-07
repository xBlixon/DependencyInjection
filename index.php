<?php
require "vendor/autoload.php";

use Velsym\DependencyManager\DependencyManager;

$dependencies = (require "testing/dependencies.php");
DependencyManager::loadDependencies($dependencies);