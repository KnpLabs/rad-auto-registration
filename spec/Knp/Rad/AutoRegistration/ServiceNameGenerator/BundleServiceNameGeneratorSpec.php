<?php

namespace spec\Knp\Rad\AutoRegistration\ServiceNameGenerator;

use Knp\Rad\AutoRegistration\Kernel\KernelWrapper;
use Knp\Rad\AutoRegistration\ServiceNameGenerator;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class BundleServiceNameGeneratorSpec extends ObjectBehavior
{
    function let(KernelWrapper $kernel, BundleInterface $bundle1, BundleInterface $bundle2, ServiceNameGenerator $generator)
    {
        $kernel->getBundles()->willReturn([$bundle1, $bundle2]);

        $this->beConstructedWith($kernel, $generator);

        $bundle1->getNamespace()->willReturn('Company\MyBundle');
        $bundle1->getName()->willReturn('bundle1');

        $bundle2->getNamespace()->willReturn('Company\MyOther\Bundle');
        $bundle2->getName()->willReturn('Bundle2');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\AutoRegistration\ServiceNameGenerator\BundleServiceNameGenerator');
    }

    function it_generates_bundle_based_on_service_names($generator)
    {
        $generator
            ->generateFromClassname('Bundle1\Form\Type\ProductType')
            ->shouldBeCalled()
            ->willReturn('bundle1.form.type.product_type')
        ;

        $this
            ->generateFromClassname('Company\MyBundle\Form\Type\ProductType')
            ->shouldReturn('bundle1.form.type.product_type')
        ;
    }

    function it_generates_bundle_based_on_other_service_names($generator)
    {
        $generator
            ->generateFromClassname('Bundle2\Twig\ProductExtension')
            ->shouldBeCalled()
            ->willReturn('bundle2.twig.product_extension')
        ;

        $this
            ->generateFromClassname('Company\MyOther\Bundle\Twig\ProductExtension')
            ->shouldReturn('bundle2.twig.product_extension')
        ;
    }

    function it_just_call_wrapped_generation_method($generator)
    {
        $generator
            ->generateFromClassname('NameSpace\The\Class')
            ->shouldBeCalled()
            ->willReturn('namespace.the.class')
        ;

        $this
            ->generateFromClassname('NameSpace\The\Class')
            ->shouldReturn('namespace.the.class')
        ;
    }
}
