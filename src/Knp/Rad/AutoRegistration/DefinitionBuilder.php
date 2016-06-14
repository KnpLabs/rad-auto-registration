<?php

namespace Knp\Rad\AutoRegistration;

interface DefinitionBuilder
{
    /**
     * @param array $config
     *
     * @return Symfony\Component\DependencyInjection\Definition[]
     */
    public function buildDefinitions(array $config);

    /**
     * @return string
     */
    public function getName();

    /**
     * @return bool
     */
    public function isActive();
}
