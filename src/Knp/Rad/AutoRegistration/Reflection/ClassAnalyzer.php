<?php

namespace Knp\Rad\AutoRegistration\Reflection;

class ClassAnalyzer
{
    public function needConstruction($class)
    {
        $class = $this->buildReflection($class);

        if (null === $constuctor = $class->getConstructor()) {
            return false;
        }

        return 0 < $constuctor->getNumberOfRequiredParameters();
    }

    private function buildReflection($class)
    {
        if (true === $class instanceof \ReflectionClass) {
            return $class;
        }

        if (true === class_exists($class)) {
            return new \ReflectionClass($class);
        }

        return;
    }
}
