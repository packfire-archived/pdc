<?php

namespace Vendor;

use Vendor\TraitZ as TraitXY;

class ClassY
{
    use TraitX;
    use \Vendor\TraitY, TraitXY;
}
