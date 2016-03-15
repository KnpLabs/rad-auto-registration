<?php

namespace spec\Knp\Rad\AutoRegistration\Finder;

use PhpSpec\ObjectBehavior;

class BundleFinderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\AutoRegistration\Finder\BundleFinder');
    }
}
