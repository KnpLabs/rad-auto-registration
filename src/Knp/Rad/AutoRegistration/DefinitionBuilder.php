<?php

namespace Knp\Rad\AutoRegistration;

interface DefinitionBuilder
{
    /**
     * @return Symfony\Component\DependencyInjection\Definition[]
     */
    public function buildDefinitions();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return bool
     */
    public function isActive();
}
