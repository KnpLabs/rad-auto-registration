<?php

namespace Knp\Rad\AutoRegistration\DependencyInjection\Compiler;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;
use Knp\Rad\AutoRegistration\DependencyInjection\ServiceNameGenerator;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;

class DoctrineObjectRepositoryPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->has('doctrine')) {
            return;
        }

        $configuration = $container->getParameter('knp_rad_auto_registration.configuration');

        if (false === $configuration['doctrine']) {
            return;
        }

        $generator = $container->get($configuration['service_name_generator']);
        $doctrine  = $container->get('doctrine');
        $metadata  = $this->getAllMetadata($doctrine, $container->get('monolog.logger.doctrine', ContainerInterface::NULL_ON_INVALID_REFERENCE));
        $definitions = $this->buildDefinitions($metadata, $container, $generator);

        foreach ($definitions as $id => $definition) {
            $container->setDefinition($id, $definition);
        }
    }

    /**
     * @param ManagerRegistry $doctrine
     * @param LoggerInterface $logger
     *
     * @return Doctrine\Common\Persistence\Mapping\ClassMetadata[]
     */
    private function getAllMetadata(ManagerRegistry $doctrine, LoggerInterface $logger = null)
    {
        $metadata = [];
        foreach ($doctrine->getManagers() as $manager) {
            try {
                $metadata = array_merge($metadata, $manager->getMetadataFactory()->getAllMetadata());
            } catch (DBALException $ex) {
                if (null !== $logger) {
                    $logger->addNotice($ex->getMessage());
                }
            }
        }

        return $metadata;
    }

    /**
     * @param ClassMetadata[] $metadata
     * @param ContainerInterface $container
     * @param ServiceNameGenerator $generator
     *
     * @return Definition[]
     */
    private function buildDefinitions(array $metadata, ContainerInterface $container, ServiceNameGenerator $generator)
    {
        $definitions = [];

        foreach ($metadata as $entity) {
            $classname  = $entity->getName();
            $repository = sprintf('%sRepository', $classname);
            $service    = $generator->generateFromClassname($repository);
            $definition = (new Definition('Doctrine\Common\Persistence\ObjectRepository'))
                ->setFactoryService('doctrine')
                ->setFactoryMethod('getRepository')
                ->addArgument($classname)
            ;

            if (true === $container->has($service)) {
                continue;
            }

            if (true === $container->hasAlias($service)) {
                continue;
            }

            $definitions[$service] = $definition;
        }

        return $definitions;
    }
}
