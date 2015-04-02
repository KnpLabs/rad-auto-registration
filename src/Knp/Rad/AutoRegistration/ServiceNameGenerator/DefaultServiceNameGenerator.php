<?php

namespace Knp\Rad\AutoRegistration\ServiceNameGenerator;

use Doctrine\Common\Inflector\Inflector;
use Knp\Rad\AutoRegistration\ServiceNameGenerator;

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
