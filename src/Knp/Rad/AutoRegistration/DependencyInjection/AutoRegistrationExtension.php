<?php

namespace Knp\Rad\AutoRegistration\DependencyInjection;

use Knp\Rad\AutoRegistration\DependencyInjection\Configuration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dump\Container;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class AutoRegistrationExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $configuration = new Configuration;
        $config        = $this->processConfiguration($configuration, $config);
        $loader        = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services.yml');

        $container->setParameter(
            sprintf('%s.bundles', $this->getAlias()),
            $this->resolveBundlesNamespace($container, $config['bundles'])
        );

        $container->setParameter(sprintf('%s.configuration', $this->getAlias()), $config);
    }

    /**
     * @param ContainerBuilder $container
     * @param array $wanted
     *
     * @return array
     */
    private function resolveBundlesNamespace(ContainerBuilder $container, array $wanted)
    {
        $bundles = array_intersect_key(
            $container->getParameter('kernel.bundles'),
            array_flip($wanted)
        );

        return array_map(function ($class, $bundleName) {
            return substr($class, 0, -strlen('\\' . $bundleName));
        }, $bundles, array_keys($bundles));
    }

    /**
     * {@inheritDoc}
     */
    public function getAlias()
    {
        return 'knp_rad_auto_registration';
    }
}
