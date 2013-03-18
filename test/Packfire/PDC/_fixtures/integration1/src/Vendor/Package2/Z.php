<?php

namespace Vendor\Package2;

use Vendor\AnotherTrait as SecondTrait;

class Z extends Y implements \Vendor\Package\AnInterface
{
    use \Vendor\SomeTrait;
    use SecondTrait;
}
