<?php

namespace spec\Knp\Rad\AutoRegistration\DefinitionBuilder;

use Knp\Rad\AutoRegistration\Finder\BundleFinder;
use Knp\Rad\AutoRegistration\Kernel\KernelWrapper;
use Knp\Rad\AutoRegistration\Reflection\ClassAnalyzer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FormTypeBuilderSpec extends ObjectBehavior
{
    public function let(KernelWrapper $kernel, BundleFinder $finder, ClassAnalyzer $analyzer)
    {
        $this->beConstructedWith($kernel, $finder, $analyzer);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\AutoRegistration\DefinitionBuilder\FormTypeBuilder');
    }

    public function it_creates_definitions_from_constructable_classes($finder, $analyzer)
    {
        $finder->findClasses(Argument::cetera())->willReturn([
            'spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Class1',
            'spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Class2',
            'spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Class3',
        ]);

        $analyzer->needConstruction('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Class1')->willReturn(false);
        $analyzer->needConstruction('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Class2')->willReturn(true);
        $analyzer->needConstruction('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Class3')->willReturn(false);

        $definitions = $this->buildDefinitions();

        $definition = $definitions['spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Class1'];
        $definition->shouldHaveType('Symfony\Component\DependencyInjection\Definition');
        $definition->getClass()->shouldReturn('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Class1');
        $definition->getTag('form.type')->shouldReturn([['alias' => 'class1']]);

        $definition = $definitions['spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Class3'];
        $definition->shouldHaveType('Symfony\Component\DependencyInjection\Definition');
        $definition->getClass()->shouldReturn('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Class3');
        $definition->getTag('form.type')->shouldReturn([['alias' => 'class3']]);

        expect(array_key_exists('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Class2', $definition))->toBe(false);
    }
}

class Class1
{
    public function getName()
    {
        return 'class1';
    }
}

class Class2
{
    public function getName()
    {
        return 'class2';
    }
}

class Class3
{
    public function getName()
    {
        return 'class3';
    }
}
