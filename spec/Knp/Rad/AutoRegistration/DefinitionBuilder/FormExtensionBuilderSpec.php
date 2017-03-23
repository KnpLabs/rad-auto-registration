<?php

namespace spec\Knp\Rad\AutoRegistration\DefinitionBuilder;

use Knp\Rad\AutoRegistration\Finder\BundleFinder;
use Knp\Rad\AutoRegistration\Kernel\KernelWrapper;
use Knp\Rad\AutoRegistration\Reflection\ClassAnalyzer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FormExtensionBuilderSpec extends ObjectBehavior
{
    function let(KernelWrapper $kernel, BundleFinder $finder, ClassAnalyzer $analyser)
    {
        $this->beConstructedWith($kernel, $finder, $analyser);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\AutoRegistration\DefinitionBuilder\FormExtensionBuilder');
    }

    function it_creates_definitions_from_constructable_classes($finder, $analyser)
    {
        $finder->findClasses(Argument::cetera())->willReturn([
            'spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Extension1',
            'spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Extension2',
            'spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Extension3',
        ]);

        $analyser->canBeConstructed('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Extension1')->willReturn(true);
        $analyser->canBeConstructed('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Extension2')->willReturn(false);
        $analyser->canBeConstructed('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Extension3')->willReturn(true);

        $definitions = $this->buildDefinitions([]);

        $definition = $definitions['spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Extension1'];
        $definition->shouldHaveType('Symfony\Component\DependencyInjection\Definition');
        $definition->getClass()->shouldReturn('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Extension1');
        $definition->isPublic()->shouldReturn(true);
        $definition->getTag('form.type_extension')->shouldReturn([['extended_type' => 'class1']]);

        $definition = $definitions['spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Extension3'];
        $definition->shouldHaveType('Symfony\Component\DependencyInjection\Definition');
        $definition->getClass()->shouldReturn('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Extension3');
        $definition->isPublic()->shouldReturn(true);
        $definition->getTag('form.type_extension')->shouldReturn([['extended_type' => 'class3']]);

        expect(array_key_exists('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Extension2', $definition))->toBe(false);
    }
}

class Extension1
{
    public function getExtendedType()
    {
        return 'class1';
    }
}

class Extension2
{
    public function getExtendedType()
    {
        return 'class2';
    }
}

class Extension3
{
    public function getExtendedType()
    {
        return 'class3';
    }
}
