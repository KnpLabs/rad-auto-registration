<?php

namespace Knp\Rad\AutoRegistration\DependencyInjection\ServiceNameGenerator;

use Doctrine\Common\Inflector\Inflector;
use Knp\Rad\AutoRegistration\DependencyInjection\ServiceNameGenerator;

class DefaultServiceNameGenerator implements ServiceNameGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generateFromClassname($classname)
    {
        return Inflector::tableize(str_replace('\\', '.', $classname));
    }
}
