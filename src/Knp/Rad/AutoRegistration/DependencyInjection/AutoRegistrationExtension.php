<?php

namespace Knp\Rad\AutoRegistration\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class AutoRegistrationExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $config);
        $loader        = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services.yml');

        $container->setParameter(sprintf('%s.configuration', $this->getAlias()), $config);
    }
    /**
     * {@inheritDoc}
     */
    public function getAlias()
    {
        return 'knp_rad_auto_registration';
    }
}
