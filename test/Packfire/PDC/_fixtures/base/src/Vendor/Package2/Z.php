<?php

namespace Vendor\Package2;

use Vendor\Package\AnInterface as NewInterface;
use Vendor\Package\X as SomeClass;

class Z extends Y implements NewInterface
{
    public function someMethod(SomeClass $someArgument) {}
}
