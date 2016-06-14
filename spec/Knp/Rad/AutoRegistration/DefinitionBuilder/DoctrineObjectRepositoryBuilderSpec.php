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
    function let(KernelWrapper $kernel, BundleFinder $finder, ManagerRegistry $doctrine, ObjectManager $om)
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

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\AutoRegistration\DefinitionBuilder\DoctrineObjectRepositoryBuilder');
    }

    function it_load_entities_definitions()
    {
        $definitions = $this->buildDefinitions(['public' => false]);

        $definition = $definitions['Bundle\Entity\Class1Repository'];
        $definition->shouldHaveType('Symfony\Component\DependencyInjection\Definition');
        $definition->getClass()->shouldReturn('Doctrine\Common\Persistence\ObjectRepository');
        $definition->isPublic()->shouldReturn(false);
        $factory = $definition->getFactory();
        expect(strval($factory[0]->getWrappedObject()))->toBe('doctrine');
        $factory[1]->shouldReturn('getRepository');
        $definition->getArguments()->shouldReturn(['Bundle\Entity\Class1']);

        $definition = $definitions['Bundle\Entity\Class3Repository'];
        $definition->shouldHaveType('Symfony\Component\DependencyInjection\Definition');
        $definition->getClass()->shouldReturn('Doctrine\Common\Persistence\ObjectRepository');
        $definition->isPublic()->shouldReturn(false);
        $factory = $definition->getFactory();
        expect(strval($factory[0]->getWrappedObject()))->toBe('doctrine');
        $factory[1]->shouldReturn('getRepository');
        $definition->getArguments()->shouldReturn(['Bundle\Entity\Class3']);

        expect(array_key_exists('Bundle\Entity\Class2Repository', $definition))->toBe(false);
    }
}
