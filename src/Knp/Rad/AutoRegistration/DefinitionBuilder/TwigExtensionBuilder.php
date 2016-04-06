<?php

namespace Knp\Rad\AutoRegistration\DefinitionBuilder;

use Knp\Rad\AutoRegistration\DefinitionBuilder;
use Knp\Rad\AutoRegistration\Finder\BundleFinder;
use Knp\Rad\AutoRegistration\Kernel\KernelWrapper;
use Knp\Rad\AutoRegistration\Reflection\ClassAnalyzer;
use Symfony\Component\DependencyInjection\Definition;

class TwigExtensionBuilder implements DefinitionBuilder
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

        $twigExtensions = $this->finder->findClasses(
            $this->kernel->getBundles(),
            ['Twig', 'Templating'],
            'Twig_Extension'
        );

        foreach ($twigExtensions as $twigExtension) {
            if (false === $this->analyzer->canBeConstructed($twigExtension)) {
                continue;
            }

            $definitions[$twigExtension] = (new Definition())
                ->setClass($twigExtension)
                ->setPublic(false)
                ->addTag('twig.extension')
            ;
        }

        return $definitions;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'twig_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return true;
    }
}
