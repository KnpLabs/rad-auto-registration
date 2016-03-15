<?php

namespace Knp\Rad\AutoRegistration\DefinitionBuilder;

use Knp\Rad\AutoRegistration\DefinitionBuilder;
use Knp\Rad\AutoRegistration\Finder\BundleFinder;
use Knp\Rad\AutoRegistration\Kernel\KernelWrapper;
use Knp\Rad\AutoRegistration\Reflection\ClassAnalyzer;
use Symfony\Component\DependencyInjection\Definition;

class FormExtensionBuilder implements DefinitionBuilder
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

        $types = $this->finder->findClasses(
            $this->kernel->getBundles(),
            'Form',
            'Symfony\Component\Form\FormTypeExtensionInterface'
        );

        foreach ($types as $type) {
            if (true === $this->analyzer->needConstruction($type)) {
                continue;
            }

            $instance           = new $type();
            $definitions[$type] = (new Definition())
                ->setClass($type)
                ->setPublic(false)
                ->addTag('form.type_extension', ['extended_type' => $instance->getExtendedType()])
            ;
        }

        return $definitions;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'form_type_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return true;
    }
}
