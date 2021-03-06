<?php

namespace spec\Knp\Rad\AutoRegistration\DefinitionBuilder;

use Knp\Rad\AutoRegistration\Finder\BundleFinder;
use Knp\Rad\AutoRegistration\Kernel\KernelWrapper;
use Knp\Rad\AutoRegistration\Reflection\ClassAnalyzer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SecurityVoterBuilderSpec extends ObjectBehavior
{
    function let(KernelWrapper $kernel, BundleFinder $finder, ClassAnalyzer $analyzer)
    {
        $this->beConstructedWith($kernel, $finder, $analyzer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\AutoRegistration\DefinitionBuilder\SecurityVoterBuilder');
    }

    function it_creates_definitions_from_constructable_classes($finder, $analyzer)
    {
        $finder->findClasses(Argument::cetera())->willReturn([
            'spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Voter1',
            'spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Voter2',
            'spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Voter3',
        ]);

        $analyzer->canBeConstructed('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Voter1')->willReturn(true);
        $analyzer->canBeConstructed('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Voter2')->willReturn(false);
        $analyzer->canBeConstructed('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Voter3')->willReturn(true);

        $definitions = $this->buildDefinitions(['public' => false]);

        $definition = $definitions['spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Voter1'];
        $definition->shouldHaveType('Symfony\Component\DependencyInjection\Definition');
        $definition->getClass()->shouldReturn('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Voter1');
        $definition->isPublic()->shouldReturn(false);
        $definition->hasTag('security.voter')->shouldReturn(true);

        $definition = $definitions['spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Voter3'];
        $definition->shouldHaveType('Symfony\Component\DependencyInjection\Definition');
        $definition->getClass()->shouldReturn('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Voter3');
        $definition->isPublic()->shouldReturn(false);
        $definition->hasTag('security.voter')->shouldReturn(true);

        expect(array_key_exists('spec\Knp\Rad\AutoRegistration\DefinitionBuilder\Voter2', $definition))->toBe(false);
    }
}

class Voter1
{
}

class Voter2
{
}

class Voter3
{
}
