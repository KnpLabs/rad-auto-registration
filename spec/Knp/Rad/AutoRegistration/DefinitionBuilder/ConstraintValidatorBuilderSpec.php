<?php

namespace spec\Knp\Rad\AutoRegistration\DefinitionBuilder;

use Knp\Rad\AutoRegistration\Finder\BundleFinder;
use Knp\Rad\AutoRegistration\Kernel\KernelWrapper;
use Knp\Rad\AutoRegistration\Reflection\ClassAnalyzer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConstraintValidatorBuilderSpec extends ObjectBehavior
{
    function let(KernelWrapper $kernel, BundleFinder $finder, ClassAnalyzer $analyser)
    {
        $this->beConstructedWith($kernel, $finder, $analyser);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\AutoRegistration\DefinitionBuilder\ConstraintValidatorBuilder');
    }

    function it_creates_definitions_from_constructable_classes($finder, $analyser)
    {
        $finder->findClasses(Argument::cetera())->willReturn([
            'spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Const1Validator',
            'spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Const2Validator',
            'spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Const3Validator',
        ]);

        $analyser
            ->canBeConstructed('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Const1Validator')
            ->willReturn(true)
        ;
        $analyser
            ->canBeConstructed('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Const2Validator')
            ->willReturn(false)
        ;
        $analyser
            ->canBeConstructed('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Const3Validator')
            ->willReturn(true)
        ;

        $definitions = $this->buildDefinitions();

        $definition = $definitions['spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Const1Validator'];
        $definition->shouldHaveType('Symfony\Component\DependencyInjection\Definition');
        $definition->getClass()->shouldReturn('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Const1Validator');
        $definition->isPublic()->shouldReturn(false);
        $definition->getTag('validator.constraint_validator')->shouldReturn([['alias_name' => 'const1']]);

        $definition = $definitions['spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Const3Validator'];
        $definition->shouldHaveType('Symfony\Component\DependencyInjection\Definition');
        $definition->getClass()->shouldReturn('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Const3Validator');
        $definition->isPublic()->shouldReturn(false);
        $definition->getTag('validator.constraint_validator')->shouldReturn([['alias_name' => 'const3']]);

        expect(array_key_exists('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Const2Validator', $definition))->toBe(false);
    }
}

class Const1Validator
{
}

class Const2Validator
{
}

class Const3Validator
{
}
