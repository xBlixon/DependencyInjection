<?php

namespace Velsym\DependencyManager;

class DependencyManager
{
    private static array $dependecies = [];

    public static function loadDependencies(array $dependencies): void
    {
        self::$dependecies = self::unpackSubDependencies($dependencies);
    }

    private static function unpackSubDependencies(array $dependencies): array
    {
        $unpackedDependencies = [];
        foreach ($dependencies as $alias => $dependency)
        {
            if(is_array($dependency))
            {
                $unpackedDependencies = array_merge($unpackedDependencies, self::unpackSubDependencies($dependency));
                unset($dependencies[$alias]);
            }
        }
        return array_merge($dependencies, $unpackedDependencies);
    }
}