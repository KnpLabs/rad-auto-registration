<?php

namespace Knp\Rad\AutoRegistration\DependencyInjection;

interface ServiceNameGenerator
{
    /**
     * @param string $name
     *
     * @return string
     */
    public function generateFromClassname($classname);
}
