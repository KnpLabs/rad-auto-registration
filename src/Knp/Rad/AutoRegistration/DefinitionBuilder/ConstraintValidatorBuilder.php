<?php

namespace Knp\Rad\AutoRegistration\DefinitionBuilder;

use Doctrine\Common\Inflector\Inflector;
use Knp\Rad\AutoRegistration\DefinitionBuilder;
use Knp\Rad\AutoRegistration\Finder\BundleFinder;
use Knp\Rad\AutoRegistration\Kernel\KernelWrapper;
use Knp\Rad\AutoRegistration\Reflection\ClassAnalyzer;
use Symfony\Component\DependencyInjection\Definition;

class ConstraintValidatorBuilder implements DefinitionBuilder
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

        $validators = $this->finder->findClasses(
            $this->kernel->getBundles(),
            ['Validator', 'Validation'],
            'Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface'
        );

        foreach ($validators as $validator) {
            if (false === $this->analyzer->canBeConstructed($validator)) {
                continue;
            }

            $definitions[$validator] = (new Definition())
                ->setClass($validator)
                ->setPublic(false)
                ->addTag('validator.constraint_validator', ['alias_name' => $this->buildAlias($validator)])
            ;
        }

        return $definitions;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'constraint_validator';
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return true;
    }

    /**
     * @param string $class
     *
     * @return string
     */
    private function buildAlias($class)
    {
        $parts     = explode('\\', $class);
        $shortName = end($parts);

        if (false === strpos($shortName, 'Validator')) {
            return Inflector::tableize($shortName);
        }

        if ('Validator' !== substr($shortName, -9)) {
            return Inflector::tableize($shortName);
        }

        return Inflector::tableize(substr($shortName, 0, -9));
    }
}
