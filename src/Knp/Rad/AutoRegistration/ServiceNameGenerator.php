<?php

namespace Knp\Rad\AutoRegistration;

interface ServiceNameGenerator
{
    /**
     * @param string $classname
     *
     * @return string
     */
    public function generateFromClassname($classname);
}
