<?php

namespace Knp\Rad\AutoRegistration\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();
        $builder
            ->root('knp_rad_auto_registration')
            ->children()
                ->scalarNode('service_name_generator')
                    ->defaultValue('knp_rad_auto_registration.dependency_injection_service_name_generator.default_servide_name_generator')
                ->end()
                ->booleanNode('doctrine')
                    ->defaultTrue()
                ->end()
            ->end()
            ;
        return $builder;
    }
}
