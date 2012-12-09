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
 * An index in the matrix
 * 
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright 2012 Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\PDC\Report
 * @since 1.0.4
 * @link https://github.com/packfire/pdc/
 */
class Index {

	private $counter = 0;

	private $summary;

	private $message;

	public function __construct($summary, $message = null){
		$this->summary = $summary;
		$this->message = $message;
	}

	public function increment(){
		++$this->counter;
	}

	public function summary(){
		return sprintf($this->summary, $this->counter);
	}

	public function message(){
		return $this->message;
	}

	public function counter(){
		return $this->counter;
	}
	
}