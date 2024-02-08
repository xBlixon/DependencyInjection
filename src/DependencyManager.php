<?php

namespace Velsym\DependencyInjection;

class DependencyManager
{
    private static array $dependencies = [];

    public static function loadDependencies(array $dependencies): void
    {
        self::$dependencies = array_merge(self::$dependencies, $dependencies);
    }

    public static function resolveClassToInstance(string $class, array $manualArguments = [])
    {
        $isWithParams = isset(self::$dependencies[$class]['params']);
        $class = self::$dependencies[$class] ?? $class;
        $classReflection = new \ReflectionClass($isWithParams ? $class['class'] : $class);
        $constructorReflection = $classReflection->getConstructor();
        if ($constructorReflection === NULL || $constructorReflection->getNumberOfParameters() === 0) {
            return $classReflection->newInstance();
        }
        $resolvedParameters = $class['params'] ?? [];
        foreach ($resolvedParameters as $name => $value) {
            $resolvedParameters[$name] = (interface_exists($value) ? self::resolveClassToInstance($value) : $value);
        }
        $resolvedParameters = array_merge($resolvedParameters, $manualArguments);
        $classParameters = $constructorReflection->getParameters();
        foreach ($classParameters as $classParameterReflection) {
            if (isset($resolvedParameters[$classParameterReflection->name]) || $classParameterReflection->isOptional()) continue;
            $resolvedParameters[$classParameterReflection->name] = self::resolveClassToInstance($classParameterReflection->getType()->getName());
        }
        return $classReflection->newInstanceArgs($resolvedParameters);
    }

    public static function callMethodWithResolvedArguments(object $object, string $methodName, array $manualArguments = []): mixed
    {
        $methodReflection = new \ReflectionMethod($object, $methodName);
        $methodParameters = $methodReflection->getParameters();
        $resolvedParameters = $manualArguments;

        foreach ($methodParameters as $methodParameterReflection) {
            if (isset($resolvedParameters[$methodParameterReflection->name]) || $methodParameterReflection->isOptional()) continue;
            $resolvedParameters[$methodParameterReflection->name] = self::resolveClassToInstance($methodParameterReflection->getType()->getName());
        }
        return $methodReflection->invokeArgs($object, $resolvedParameters);
    }

    public static function getDependencies(): array
    {
        return self::$dependencies;
    }
}