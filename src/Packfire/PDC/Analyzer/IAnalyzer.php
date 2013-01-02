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

use Packfire\PDC\Report\IReport;

/**
 * Analyzes source code for namespace, class declaration and usage
 * 
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright Sam-Mauris Yong <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\PDC\Analyzer
 * @since 1.0.8
 * @link https://github.com/packfire/pdc/
 */
interface IAnalyzer {
    
    public function __construct($file);
    
    public function analyze(IReport $report);
    
}