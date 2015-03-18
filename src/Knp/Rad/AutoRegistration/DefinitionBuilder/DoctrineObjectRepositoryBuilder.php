<?php

namespace Knp\Rad\AutoRegistration\DefinitionBuilder;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Exception\ConnectionException;
use Knp\Rad\AutoRegistration\DefinitionBuilder;
use Knp\Rad\AutoRegistration\Finder\BundleFinder;
use Knp\Rad\AutoRegistration\Kernel\KernelWrapper;
use Symfony\Component\DependencyInjection\Definition;

class DoctrineObjectRepositoryBuilder implements DefinitionBuilder
{
    /**
     * @var KernelWrapper
     */
    private $kernel;

    /**
     * @var BundleFinder
     */
    private $finder;

    /**
     * @var ManagerRegistry
     */
    private $om;

    /**
     * @var string
     */
    private $name;

    /**
     * @param KernelWrapper        $kernel
     * @param BundleFinder         $finder
     * @param ManagerRegistry|null $om
     * @param string|null          $name
     */
    public function __construct(KernelWrapper $kernel, BundleFinder $finder, ManagerRegistry $om = null, $name = 'doctrine')
    {
        $this->kernel = $kernel;
        $this->finder = $finder;
        $this->om     = $om;
        $this->name   = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function buildDefinitions()
    {
        $definitions = [];

        $objects = $this->finder->findClasses(
            $this->kernel->getBundles(),
            ['Entity', 'Document', 'Model']
        );

        foreach ($objects as $object) {
            try {
                if (null === $this->om->getManagerForClass($object)) {
                    continue;
                }
            } catch (ConnectionException $ex) {
                unset($ex);
            }

            $definitions[sprintf('%sRepository', $object)] = (new Definition())
                ->setClass('Doctrine\Common\Persistence\ObjectRepository')
                ->setFactoryService($this->name)
                ->setFactoryMethod('getRepository')
                ->addArgument($object)
            ;
        }

        return $definitions;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return null !== $this->om;
    }
}
