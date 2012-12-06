<?php
/**
 * Packfire Dependency Checker (pdc)
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) 2012, Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\PDC;

/**
 * Utility Toolbelt for the package
 * 
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright 2012 Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\PDC
 * @since 1.0.4
 * @link https://github.com/packfire/pdc/
 */
class Toolbelt {

	/**
	 * Get the class name from the entire namespace
	 * @param string $namespace The full namespace to extract the class name
	 * @return string Returns the class name extracted from the namespace
	 * @since 1.0.4
	 */
	public static function classFromNamespace($namespace){
	    return ltrim(substr($namespace, strrpos($namespace, '\\')), '\\');
	}

}