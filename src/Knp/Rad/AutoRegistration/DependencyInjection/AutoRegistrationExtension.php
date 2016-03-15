<?php

namespace Knp\Rad\AutoRegistration\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class AutoRegistrationExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $config);
        $loader        = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services.xml');

        $container->setParameter(sprintf('%s.configuration', $this->getAlias()), $config);
    }
    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'knp_rad_auto_registration';
    }
}
