<?php

namespace Knp\Rad\AutoRegistration\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DefinitionBuilderActivationPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $configuration = $container->getParameter('knp_rad_auto_registration.configuration');
        $services      = $configuration['services'];
        $generator     = $container
            ->get('knp_rad_auto_registration.service_name_generator.bundle_service_name_generator')
        ;

        foreach ($container->findTaggedServiceIds('knp_rad_auto_registration.definition_builder') as $id => $tags) {
            $builder = $container->get($id);

            if (false === $builder->isActive()) {
                continue;
            }

            if (false === $services[$builder->getName()]) {
                continue;
            }

            $definitions = $builder->buildDefinitions();

            foreach ($definitions as $class => $definition) {
                $serviceId = $generator->generateFromClassname($class);

                if (true === $container->hasDefinition($serviceId)) {
                    continue;
                }

                if (true === $container->hasAlias($serviceId)) {
                    continue;
                }

                $container->setDefinition($serviceId, $definition);
            }
        }
    }
}
