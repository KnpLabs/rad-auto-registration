<?php

namespace spec\Knp\Rad\AutoRegistration\DefinitionBuilder;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\Rad\AutoRegistration\Kernel\KernelWrapper;
use Knp\Rad\AutoRegistration\Finder\BundleFinder;
use Knp\Rad\AutoRegistration\Reflection\ClassAnalyzer;

class TwigExtensionBuilderSpec extends ObjectBehavior
{
    public function let(KernelWrapper $kernel, BundleFinder $finder, ClassAnalyzer $analyzer)
    {
        $this->beConstructedWith($kernel, $finder, $analyzer);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\AutoRegistration\DefinitionBuilder\TwigExtensionBuilder');
    }

    public function it_create_definitions_from_constructable_twig_extensions($finder, $analyzer)
    {
        $finder->findClasses(Argument::cetera())->willReturn([
            'spec\Knp\Rad\AutoRegistration\DefinitionBuilder\TwigExtension1',
            'spec\Knp\Rad\AutoRegistration\DefinitionBuilder\TwigExtension2',
            'spec\Knp\Rad\AutoRegistration\DefinitionBuilder\TwigExtension3',
        ]);

        $analyzer->needConstruction('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\TwigExtension1')->willReturn(false);
        $analyzer->needConstruction('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\TwigExtension2')->willReturn(true);
        $analyzer->needConstruction('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\TwigExtension3')->willReturn(false);

        $definitions = $this->buildDefinitions();

        $definition = $definitions['spec\Knp\Rad\AutoRegistration\DefinitionBuilder\TwigExtension1'];
        $definition->shouldHaveType('Symfony\Component\DependencyInjection\Definition');
        $definition->getClass()->shouldReturn('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\TwigExtension1');

        $definition = $definitions['spec\Knp\Rad\AutoRegistration\DefinitionBuilder\TwigExtension3'];
        $definition->shouldHaveType('Symfony\Component\DependencyInjection\Definition');
        $definition->getClass()->shouldReturn('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\TwigExtension3');

        expect(array_key_exists('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\TwigExtension2', $definition))->toBe(false);
    }
}

class TwigExtension1
{
}

class TwigExtension2
{
}

class TwigExtension3
{
}
