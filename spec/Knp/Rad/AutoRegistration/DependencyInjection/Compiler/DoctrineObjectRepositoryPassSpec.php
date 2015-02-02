<?php

namespace spec\Knp\Rad\AutoRegistration\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrineObjectRepositoryPassSpec extends ObjectBehavior
{
    function let(ContainerBuilder $container)
    {
        $container->has('doctrine')->willReturn(true);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\AutoRegistration\DependencyInjection\Compiler\DoctrineObjectRepositoryPass');
    }

    function it_doesnt_do_anything_if_doctrine_is_not_loaded($container)
    {
        $container->has('doctrine')->willReturn(false);

        $container->setDefinition(Argument::cetera())->shouldNotBeCalled();
    }
}
