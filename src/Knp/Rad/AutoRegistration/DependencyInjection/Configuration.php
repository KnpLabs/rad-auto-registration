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
                ->arrayNode('enable')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('doctrine')
                            ->defaultFalse()
                            ->treatNullLike(true)
                        ->end()
                        ->booleanNode('doctrine_mongodb')
                            ->defaultFalse()
                            ->treatNullLike(true)
                        ->end()
                        ->booleanNode('doctrine_couchdb')
                            ->defaultFalse()
                            ->treatNullLike(true)
                        ->end()
                        ->booleanNode('form_type_extension')
                            ->defaultFalse()
                            ->treatNullLike(true)
                        ->end()
                        ->booleanNode('security_voter')
                            ->defaultFalse()
                            ->treatNullLike(true)
                        ->end()
                        ->booleanNode('twig_extension')
                            ->defaultFalse()
                            ->treatNullLike(true)
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
