<?php

/**
 * Packfire Dependency Checker (pdc)
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\PDC;

use Packfire\Concrete\Compiler as CoreCompiler;
use Packfire\Concrete\Processor\StripWhiteSpace;
use Packfire\Concrete\Processor\License;

/**
 * Helps to provide compilation into a PHAR binary
 * 
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright Sam-Mauris Yong <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\PDC
 * @since 1.0.4
 * @link https://github.com/packfire/pdc/
 */

class Compiler extends CoreCompiler {
    
    protected function compile(){
        $this->processor = new License();
        $this->addFile(new \SplFileInfo(__DIR__ . '/../../../license'));
        $this->processor = new StripWhiteSpace();
        $this->addFolder(__DIR__ . '/../../');
        $this->addFolder(__DIR__ . '/../../../bin/');
        $this->addFolder(__DIR__ . '/../../../vendor/composer');
        $this->addFolder(__DIR__ . '/../../../vendor/packfire/options');
        $this->addFile(new \SplFileInfo(__DIR__ . '/../../../vendor/autoload.php'));
    }
    
    protected function stub(){
        return <<<'EOF'
#!/usr/bin/env php
<?php
/**
 * Packfire Dependency Checker (pdc)
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) 2012, Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * All rights reserved.
 */

Phar::mapPhar('pdc.phar');

require 'phar://pdc.phar/bin/pdc';

__HALT_COMPILER();
EOF;
    }
    
}
