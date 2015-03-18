<?php

namespace Knp\Rad\AutoRegistration\Kernel;

use Symfony\Component\HttpKernel\KernelInterface;

class KernelWrapper
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var string[]
     */
    private $bundles;

    /**
     * @param KernelInterface $kernel
     * @param array           $config
     */
    public function __construct(KernelInterface $kernel, array $config)
    {
        $this->kernel  = $kernel;
        $this->bundles = $config['bundles'];
    }

    /**
     * @return Symfony\Component\HttpKernel\Bundle\BundleInterface[]
     */
    public function getBundles()
    {
        $bundles = $this->kernel->getBundles();

        if (true === empty($this->bundles)) {
            return $bundles;
        }

        $return = [];

        foreach ($bundles as $bundle) {
            if (true === in_array($bundle->getName(), $this->bundles)) {
                $return[] = $bundle;
            }
        }

        return $return;
    }
}
