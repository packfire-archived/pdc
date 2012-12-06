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

use Packfire\Command\OptionSet;

/**
 * The main PDC class for running and processing the source code
 * 
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright 2012 Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\PDC
 * @since 1.0.4
 * @link https://github.com/packfire/pdc/
 */
class PDC {

	/**
	 * Path to autoloader
	 * @var string
	 * @since 1.0.4
	 */
	private $autoloader;

	/**
	 * Path to source code folder
	 * @var string
	 * @since 1.0.4
	 */
	private $path;

	public function __construct($args){
		$optionSet = new OptionSet($args);
		$optionSet->addIndex(2, array($this, 'setAutoLoader'));
	}

	public function run(){

	}

	public function setPath($path){
		$this->path = $path;
	}

	public function setAutoLoader($autoLoader){
		$this->autoLoader = $autoLoader;
	}

}