<?php

/**
 * Packfire Dependency Checker (pdc)
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\PDC\Report;

/**
 * A Report Interface
 * 
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright Sam-Mauris Yong <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\PDC\Report
 * @since 1.0.8
 * @link https://github.com/packfire/pdc/
 */
interface IReport {
    
    public function add($key, IIndex $index);
    
    public function processFile($file);
    
    public function increment($key, $details = null);
    
    public function report();
    
}