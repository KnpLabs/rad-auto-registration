<?php

namespace spec\Knp\Rad\AutoRegistration\DefinitionBuilder;

use Knp\Rad\AutoRegistration\Finder\BundleFinder;
use Knp\Rad\AutoRegistration\Kernel\KernelWrapper;
use Knp\Rad\AutoRegistration\Reflection\ClassAnalyzer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FormExtensionBuilderSpec extends ObjectBehavior
{
    public function let(KernelWrapper $kernel, BundleFinder $finder, ClassAnalyzer $analizer)
    {
        $this->beConstructedWith($kernel, $finder, $analizer);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\AutoRegistration\DefinitionBuilder\FormExtensionBuilder');
    }

    public function it_creates_definitions_from_constructable_classes($finder, $analizer)
    {
        $finder->findClasses(Argument::cetera())->willReturn([
            'spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Extension1',
            'spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Extension2',
            'spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Extension3',
        ]);

        $analizer->needConstruction('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Extension1')->willReturn(false);
        $analizer->needConstruction('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Extension2')->willReturn(true);
        $analizer->needConstruction('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Extension3')->willReturn(false);

        $definitions = $this->buildDefinitions();

        $definition = $definitions['spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Extension1'];
        $definition->shouldHaveType('Symfony\Component\DependencyInjection\Definition');
        $definition->getClass()->shouldReturn('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Extension1');
        $definition->getTag('form.type_extension')->shouldReturn([['alias' => 'class1']]);

        $definition = $definitions['spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Extension3'];
        $definition->shouldHaveType('Symfony\Component\DependencyInjection\Definition');
        $definition->getClass()->shouldReturn('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Extension3');
        $definition->getTag('form.type_extension')->shouldReturn([['alias' => 'class3']]);

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
