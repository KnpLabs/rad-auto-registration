<?php

namespace Knp\Rad\AutoRegistration\Bundle;

use Knp\Rad\AutoRegistration\DependencyInjection\AutoRegistrationExtension;
use Knp\Rad\AutoRegistration\DependencyInjection\Compiler\DoctrineObjectRepositoryPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AutoRegistrationBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new DoctrineObjectRepositoryPass, PassConfig::TYPE_OPTIMIZE);
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
