<?php

namespace Knp\Rad\AutoRegistration\Reflection;

use ReflectionClass;

class ClassAnalyzer
{
    /**
     * @param string $class
     *
     * @return bool Return TRUE if class does not have constructor (or without required parameter) and if class is not abstract, FALSE else
     */
    public function canBeConstructed($class)
    {
        $class = $this->buildReflection($class);

        if ($class->isInterface()) {
            return false;
        }

        if ($class->isAbstract()) {
            return false;
        }

        if (null === $constuctor = $class->getConstructor()) {
            return true;
        }

        return 0 === $constuctor->getNumberOfRequiredParameters();
    }

    /**
     * @param string|ReflectionClass $class
     *
     * @return ReflectionClass|null
     */
    private function buildReflection($class)
    {
        if ($class instanceof ReflectionClass) {
            return $class;
        }

        if (class_exists($class)) {
            return new ReflectionClass($class);
        }

        return;
    }
}
