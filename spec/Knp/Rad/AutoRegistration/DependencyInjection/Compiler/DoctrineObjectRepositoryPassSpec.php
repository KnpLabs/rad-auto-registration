<?php

namespace spec\Knp\Rad\AutoRegistration\DependencyInjection\Compiler;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Rad\AutoRegistration\DependencyInjection\ServiceNameGenerator\DefaultServiceNameGenerator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrineObjectRepositoryPassSpec extends ObjectBehavior
{
    function let(ContainerBuilder $container, ManagerRegistry $doctrine, ObjectManager $em1, ObjectManager $em2, ClassMetadataFactory $mf1, ClassMetadataFactory $mf2, ClassMetadata $meta1, ClassMetadata $meta2, \ReflectionClass $reflClass1, \ReflectionClass $reflClass2)
    {
        $generator = new DefaultServiceNameGenerator;

        $container->has('doctrine')->willReturn(true);
        $container->get('doctrine')->willReturn($doctrine);
        $container->get('generator')->willReturn($generator);
        $container->get('monolog.logger.doctrine', Argument::any())->willReturn(null);
        $container->getParameter('knp_rad_auto_registration.bundles')->willReturn(['AppBundle' => 'App', 'ApiBundle' => 'Api']);
        $container->getParameter('knp_rad_auto_registration.configuration')->willReturn(['service_name_generator' => 'generator', 'doctrine' => true, 'doctrine_odm' => false]);
        $container->has('app.entity.user_repository')->willReturn(false);
        $container->hasAlias('app.entity.user_repository')->willReturn(false);
        $container->has('api.model.operator_repository')->willReturn(false);
        $container->hasAlias('api.model.operator_repository')->willReturn(false);

        $doctrine->getManagers()->willReturn([ $em1, $em2 ]);
        $em1->getMetadataFactory()->willReturn($mf1);
        $em2->getMetadataFactory()->willReturn($mf2);
        $mf1->getAllMetadata()->willReturn([$meta1]);
        $mf2->getAllMetadata()->willReturn([$meta2]);

        $meta1->getReflectionClass()->willReturn($reflClass1);
        $meta2->getReflectionClass()->willReturn($reflClass2);

        $reflClass1->getNamespaceName()->willReturn('App\Entity');
        $reflClass2->getNamespaceName()->willReturn('Api\Model');

        $meta1->getName()->willReturn('App\Entity\User');
        $meta2->getName()->willReturn('Api\Model\Operator');

        $this->beConstructedWith('doctrine', 'doctrine');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\AutoRegistration\DependencyInjection\Compiler\DoctrineObjectRepositoryPass');
    }

    function it_doesnt_do_anything_if_doctrine_is_not_loaded($container)
    {
        $container->has('doctrine')->willReturn(false);
        $container->setDefinition(Argument::cetera())->shouldNotBeCalled();

        $this->shouldThrow('\RuntimeException')->during('process', [$container]);
    }

    function it_generates_definitions($container)
    {
        $container->has('doctrine')->willReturn(true);

        $container->setDefinition('app.entity.user_repository', Argument::type('Symfony\Component\DependencyInjection\Definition'))->shouldBeCalled();
        $container->setDefinition('api.model.operator_repository', Argument::type('Symfony\Component\DependencyInjection\Definition'))->shouldBeCalled();

        $this->process($container);
    }

    function it_doesnt_erase_existing_services($container)
    {
        $container->has('doctrine')->willReturn(true);
        $container->has('api.model.operator_repository')->willReturn(true);

        $container->setDefinition('app.entity.user_repository', Argument::type('Symfony\Component\DependencyInjection\Definition'))->shouldBeCalled();
        $container->setDefinition('api.model.operator_repository', Argument::type('Symfony\Component\DependencyInjection\Definition'))->shouldNotBeCalled();

        $this->process($container);
    }
}
