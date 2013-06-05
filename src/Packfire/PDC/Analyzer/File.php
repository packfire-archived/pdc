<?php

/**
 * Packfire Dependency Checker (pdc)
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\PDC\Analyzer;

/**
 * File to be analyzed
 * 
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright Sam-Mauris Yong <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\PDC\Analyzer
 * @since 1.0.8
 * @link https://github.com/packfire/pdc/
 */
class File implements IFile {
    
    private $path;
    
    public function __construct($path) {
        $this->path = $path;
    }

    public function className() {
        return preg_replace('{\.php$}', '', basename($this->path));
        return preg_replace('{\.php$}i', '', basename($this->path));
    }

    public function path() {
        return $this->path;
    }

    public function source() {
        return file_get_contents($this->path);
    }
    
}