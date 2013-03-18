<?php

namespace Vendor\Package2;

use Vendor\Package;
use DateTime as BaseClass;

class Y extends BaseClass
{
    public function action(BaseClass $arg)
    {
        $x = new Package\X('now');
    }
}
