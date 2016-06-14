<?php

namespace Knp\Rad\AutoRegistration\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $builders = [
            'constraint_validator',
            'doctrine',
            'doctrine_mongodb',
            'form_type_extension',
            'security_voter',
            'twig_extension',
        ];

        $nodes = new ArrayNodeDefinition('enable');

        foreach ($builders as $builder) {
            $nodes->children()->append($this->buildDefinitionBuilderNode($builder));
        }

        $builder = new TreeBuilder();
        $builder
            ->root('knp_rad_auto_registration')
            ->children()
                ->append($nodes)
                ->arrayNode('bundles')
                    ->prototype('scalar')->end()
                    ->defaultValue([])
                ->end()
            ->end()
        ;

        return $builder;
    }

    /**
     * @param string $name
     *
     * @return ArrayNodeDefinition
     */
    private function buildDefinitionBuilderNode($name)
    {
        $node = new ArrayNodeDefinition($name);

        $node
            ->children()
                ->booleanNode('public')
                    ->defaultFalse()
                    ->treatNullLike(false)
                ->end()
            ->end()
        ;

        return $node;
    }
}
