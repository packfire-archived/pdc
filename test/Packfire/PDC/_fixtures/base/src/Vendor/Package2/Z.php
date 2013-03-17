<?php

namespace Vendor\Package2;

use Vendor\Package\X as SomeClass;

class Z extends Y implements \Vendor\Package\AnInterface
{
    public function someMethod(SomeClass $someArgument) {}
}
