<?php
/**
 * Packfire Dependency Checker (pdc)
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) 2012, Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\PDC\Report;

/**
 * Enumeration of report types
 * 
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright 2012 Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\PDC\Report
 * @since 1.0.4
 * @link https://github.com/packfire/pdc/
 */
class ReportType {

	const FILE = 100;
	const MISMATCH = 200;
	const NO_NAMESPACE = 300;
	const NOT_FOUND = 400;
	const UNUSED = 500;
	
}