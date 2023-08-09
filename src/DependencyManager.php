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
        foreach ($dependencies as $alias => $dependency) {
            if (is_array($dependency)) {
                $unpackedDependencies = array_merge($unpackedDependencies, self::unpackSubDependencies($dependency));
                unset($dependencies[$alias]);
            }
        }
        return array_merge($dependencies, $unpackedDependencies);
    }

    public static function resolveClassToInstance(string $class)
    {
        $class = self::$dependecies[$class] ?? $class;
        $classReflection = new \ReflectionClass($class);
        $constructorReflection = $classReflection->getConstructor();
        if ($constructorReflection === NULL || $constructorReflection->getNumberOfRequiredParameters() === 0) {
            return $classReflection->newInstance();
        }
        $resolvedParameters = [];
        $classParameters = $constructorReflection->getParameters();
        foreach ($classParameters as $classParameterReflection) {
            if ($classParameterReflection->isOptional()) {
                continue;
            }
            $resolvedParameters[] = self::resolveClassToInstance($classParameterReflection->getType()->getName());
        }
        return $classReflection->newInstanceArgs($resolvedParameters);
    }
}