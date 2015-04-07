<?php

namespace spec\Knp\Rad\AutoRegistration\DefinitionBuilder;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Rad\AutoRegistration\Finder\BundleFinder;
use Knp\Rad\AutoRegistration\Kernel\KernelWrapper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DoctrineObjectRepositoryBuilderSpec extends ObjectBehavior
{
    public function let(KernelWrapper $kernel, BundleFinder $finder, ManagerRegistry $doctrine, ObjectManager $om)
    {
        $this->beConstructedWith($kernel, $finder, $doctrine);

        $doctrine->getManagerForClass('Bundle\Entity\Class1')->willReturn($om);
        $doctrine->getManagerForClass('Bundle\Entity\Class2')->willReturn(null);
        $doctrine->getManagerForClass('Bundle\Entity\Class3')->willReturn($om);

        $finder->findClasses(Argument::cetera())->willReturn([
            'Bundle\Entity\Class1',
            'Bundle\Entity\Class2',
            'Bundle\Entity\Class3',
        ]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\AutoRegistration\DefinitionBuilder\DoctrineObjectRepositoryBuilder');
    }

    public function it_load_entities_definitions()
    {
        $definitions = $this->buildDefinitions();

        $definition = $definitions['Bundle\Entity\Class1Repository'];
        $definition->shouldHaveType('Symfony\Component\DependencyInjection\Definition');
        $definition->getClass()->shouldReturn('Doctrine\Common\Persistence\ObjectRepository');
        $definition->getArguments()->shouldReturn(['Bundle\Entity\Class1']);
        $factory = $definition->getFactory();
        $factory = current($factory);
        expect((string) current($factory))->toBe('doctrine');
        expect(end($factory))->toBe('getRepository');

        $definition = $definitions['Bundle\Entity\Class3Repository'];
        $definition->shouldHaveType('Symfony\Component\DependencyInjection\Definition');
        $definition->getClass()->shouldReturn('Doctrine\Common\Persistence\ObjectRepository');
        $definition->getArguments()->shouldReturn(['Bundle\Entity\Class3']);
        $factory = $definition->getFactory();
        $factory = current($factory);
        expect((string) current($factory))->toBe('doctrine');
        expect(end($factory))->toBe('getRepository');

        expect(array_key_exists('Bundle\Entity\Class2Repository', $definition))->toBe(false);
    }
}
