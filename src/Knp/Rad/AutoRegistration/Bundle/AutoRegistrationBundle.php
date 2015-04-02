<?php

namespace Knp\Rad\AutoRegistration\Bundle;

use Knp\Rad\AutoRegistration\DependencyInjection\AutoRegistrationExtension;
use Knp\Rad\AutoRegistration\DependencyInjection\Compiler\DefinitionBuilderActivationPass;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\Compiler\FormPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;

class AutoRegistrationBundle extends Bundle
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->set('knp_rad_auto_registration.kernel', $this->kernel);

        $container->addCompilerPass(new DefinitionBuilderActivationPass([
            'doctrine', 'doctrine_mongodb', 'doctrine_couchdb'
        ]), PassConfig::TYPE_OPTIMIZE);

        $container->addCompilerPass(new DefinitionBuilderActivationPass([
            'form_type', 'form_type_extension'
        ]), PassConfig::TYPE_BEFORE_OPTIMIZATION);
        $container->addCompilerPass(new FormPass());
    }

    /**
     * {@inheritDoc}
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new AutoRegistrationExtension();
        }

        return $this->extension;
    }
}
