<?php

namespace Knp\Rad\AutoRegistration\DependencyInjection\Compiler;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
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
     * @var string
     */
    private $configItem;

    /**
     * @var string
     */
    private $serviceName;


    public function __construct($configItem, $serviceName)
    {
        $this->configItem  = $configItem;
        $this->serviceName = $serviceName;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $bundles       = $container->getParameter('knp_rad_auto_registration.bundles');
        $configuration = $container->getParameter('knp_rad_auto_registration.configuration');

        $configItem = $this->configItem;

        if (false === $configuration[$configItem]) {
            return;
        }

        if (false === $container->has($this->serviceName)) {
            throw new \RuntimeException(sprintf('Service "%s" is unavailable.', $this->serviceName));
        }

        $generator = $container->get($configuration['service_name_generator']);
        $doctrine  = $container->get($this->serviceName);
        $metadata  = $this->getAllMetadata($doctrine, $container->get('monolog.logger.doctrine', ContainerInterface::NULL_ON_INVALID_REFERENCE));
        $definitions = $this->buildDefinitions($this->filterMetadata($metadata, $bundles), $container, $generator);

        foreach ($definitions as $id => $definition) {
            $container->setDefinition($id, $definition);
        }
    }

    /**
     * @param ManagerRegistry $doctrine
     * @param LoggerInterface $logger
     *
     * @return ClassMetadata[]
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
     * @param array $bundles
     *
     * @return ClassMetadata[]
     */
    private function filterMetadata(array $metadata, array $bundles) {
        return array_filter($metadata, function (ClassMetadata $metadata) use ($bundles) {
            $reflClass = $metadata->getReflectionClass();

            $matches = [];

            preg_match('#^(?P<bundleNs>.*)\\\(?:Entity|Model|Document)$#', $reflClass->getNamespaceName(), $matches);

            return isset($matches['bundleNs']) && in_array($matches['bundleNs'], $bundles);
        });
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

            if (true === $container->has($service)) {
                continue;
            }

            if (true === $container->hasAlias($service)) {
                continue;
            }

            $definitions[$service] = (new Definition('Doctrine\Common\Persistence\ObjectRepository'))
                ->setFactoryService($this->serviceName)
                ->setFactoryMethod('getRepository')
                ->addArgument($classname)
            ;
        }

        return $definitions;
    }
}
