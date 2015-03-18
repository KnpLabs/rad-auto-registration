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
     * @return boolean
     */
    public function isActive();
}
