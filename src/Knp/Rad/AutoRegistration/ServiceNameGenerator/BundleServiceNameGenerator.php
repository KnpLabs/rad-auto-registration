<?php

namespace Knp\Rad\AutoRegistration\ServiceNameGenerator;

use Doctrine\Common\Inflector\Inflector;
use Knp\Rad\AutoRegistration\Kernel\KernelWrapper;
use Knp\Rad\AutoRegistration\ServiceNameGenerator;

class BundleServiceNameGenerator implements ServiceNameGenerator
{
    /**
     * @var KernelWrapper
     */
    private $kernel;

    /**
     * @var ServiceNameGenerator
     */
    private $generator;

    /**
     * @param KernelWrapper        $kernel
     * @param ServiceNameGenerator $generator
     */
    public function __construct(KernelWrapper $kernel, ServiceNameGenerator $generator)
    {
        $this->kernel    = $kernel;
        $this->generator = $generator;
    }

    /**
     * {@inheritdoc}
     */
    public function generateFromClassname($classname)
    {
        $bundles = $this->kernel->getBundles();

        foreach ($bundles as $bundle) {
            if (0 !== strpos($classname, $bundle->getNamespace())) {
                continue;
            }

            $classname = sprintf(
                '%s\%s',
                Inflector::classify($bundle->getName()),
                trim(substr($classname, strlen($bundle->getNamespace())), '\\')
            );
        }

        return $this->generator->generateFromClassname($classname);
    }
}
