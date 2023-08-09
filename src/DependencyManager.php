<?php

namespace Velsym\DependencyInjection;

class DependencyManager
{
    private static array $dependencies = [];

    public static function loadDependencies(array $dependencies): void
    {
        self::$dependencies = array_merge(self::$dependencies, $dependencies);
    }

    public static function resolveClassToInstance(string $class)
    {
        $isWithParams = isset(self::$dependencies[$class]) && is_array(self::$dependencies[$class]);
        $class = self::$dependencies[$class] ?? $class;
        $classReflection = new \ReflectionClass($isWithParams ? $class['class'] : $class);
        $constructorReflection = $classReflection->getConstructor();
        if ($constructorReflection === NULL || $constructorReflection->getNumberOfParameters() === 0) {
            return $classReflection->newInstance();
        }
        $resolvedParameters = $class['params'] ?? [];
        $classParameters = $constructorReflection->getParameters();
        foreach ($classParameters as $classParameterReflection) {
            if ($classParameterReflection->isOptional()) {
                continue;
            }
            $resolvedParameters[$classParameterReflection->name] = self::resolveClassToInstance($classParameterReflection->getType()->getName());
        }
        return $classReflection->newInstanceArgs($resolvedParameters);
    }

    public static function getDependencies(): array
    {
        return self::$dependencies;
    }
}