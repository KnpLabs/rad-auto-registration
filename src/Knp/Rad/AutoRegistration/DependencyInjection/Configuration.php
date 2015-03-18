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
                ->arrayNode('services')
                    ->children()
                        ->booleanNode('doctrine')
                            ->defaultFalse()
                        ->end()
                        ->booleanNode('doctrine_mongodb')
                            ->defaultFalse()
                        ->end()
                        ->booleanNode('doctrine_couchdb')
                            ->defaultFalse()
                        ->end()
                        ->booleanNode('form_type')
                            ->defaultFalse()
                        ->end()
                        ->booleanNode('form_type_extension')
                            ->defaultFalse()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('bundles')
                    ->prototype('scalar')->end()
                    ->defaultValue([])
                ->end()
            ->end()
        ;

        return $builder;
    }
}
