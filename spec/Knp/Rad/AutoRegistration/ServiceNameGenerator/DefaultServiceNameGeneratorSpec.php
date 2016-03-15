<?php

namespace spec\Knp\Rad\AutoRegistration\ServiceNameGenerator;

use PhpSpec\ObjectBehavior;

class DefaultServiceNameGeneratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\AutoRegistration\ServiceNameGenerator\DefaultServiceNameGenerator');
    }

    function it_generates_service_names()
    {
        $this->generateFromClassname('Doctrine\Common\Inflector\Inflector')->shouldReturn('doctrine.common.inflector.inflector');
        $this->generateFromClassname('Symfony\Component\DependencyInjection\Extension\Extension')->shouldReturn('symfony.component.dependency_injection.extension.extension');
    }
}
