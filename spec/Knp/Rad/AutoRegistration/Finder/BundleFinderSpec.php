<?php

namespace spec\Knp\Rad\AutoRegistration\Finder;

use PhpSpec\ObjectBehavior;

class BundleFinderSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\AutoRegistration\Finder\BundleFinder');
    }
}
