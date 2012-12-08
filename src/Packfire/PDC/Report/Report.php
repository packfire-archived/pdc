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
 * Report holding object
 * 
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright 2012 Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\PDC\Report
 * @since 1.0.4
 * @link https://github.com/packfire/pdc/
 */
class Report {

	private $indexes = array();

	public function add($key, $index){
		if($index instanceof Index){
			$this->indexes[$key] = $index;
		}else{
			throw new InvalidArgumentException('Report::add() expected $index to be an object of \Packfire\PDC\Report\Index.');
		}
	}

	public function increment($key){
		if(isset($this->indexes[$key])){
			$this->indexes[$key]->increment();
		}else{
			throw new Exception($key . ' index not found in report.');
		}
	}
	
}