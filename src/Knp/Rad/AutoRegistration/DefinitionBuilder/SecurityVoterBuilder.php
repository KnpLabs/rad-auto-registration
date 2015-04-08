<?php

namespace Knp\Rad\AutoRegistration\DefinitionBuilder;

use Knp\Rad\AutoRegistration\DefinitionBuilder;
use Knp\Rad\AutoRegistration\Finder\BundleFinder;
use Knp\Rad\AutoRegistration\Kernel\KernelWrapper;
use Knp\Rad\AutoRegistration\Reflection\ClassAnalyzer;
use Symfony\Component\DependencyInjection\Definition;

class SecurityVoterBuilder implements DefinitionBuilder
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
     * @var ClassAnalyzer
     */
    private $analyzer;

    /**
     * @param KernelWrapper $kernel
     * @param BundleFinder  $finder
     * @param ClassAnalyzer $analyzer
     */
    public function __construct(KernelWrapper $kernel, BundleFinder $finder, ClassAnalyzer $analyzer)
    {
        $this->kernel   = $kernel;
        $this->finder   = $finder;
        $this->analyzer = $analyzer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildDefinitions()
    {
        $definitions = [];

        $voters = $this->finder->findClasses(
            $this->kernel->getBundles(),
            'Security',
            'Symfony\Component\Security\Core\Authorization\Voter\VoterInterface'
        );

        foreach ($voters as $voter) {
            if (true === $this->analyzer->needConstruction($voter)) {
                continue;
            }

            $definitions[$voter] = (new Definition())
                ->setClass($voter)
                ->addTag('security.voter')
            ;
        }

        return $definitions;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'security_voter';
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return true;
    }
}
