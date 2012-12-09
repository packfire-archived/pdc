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

use Packfire\PDC\Report\ReportType;

/**
 * Analyzes source code for namespace, class declaration and usage
 * 
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright 2012 Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\PDC
 * @since 1.0.4
 * @link https://github.com/packfire/pdc/
 */
class Analyzer {

	/**
	 * The file info object
	 * @var SplFileInfo
	 * @since 1.0.4
	 */
	private $info;

	/**
	 * Original PHP source code
	 * @var array
	 * @since 1.0.4
	 */
	private $source;

	/**
	 * PHP source code tokens
	 * @var array
	 * @since 1.0.4
	 */
	private $tokens;

	/**
	 * The cache of number of tokens
	 * @var integer
	 * @since 1.0.4
	 */
	private $count;

	/**
	 * Create a new Analyzer object
	 * @param string|\SplFileInfo $file Path name to the PHP file to analyze
	 * @since 1.0.4
	 */
	public function __construct($file){
		if($file instanceof \SplFileInfo){
			$this->info = $file;
		}else{
			$this->info = new \SplFileInfo($file);
		}
		$this->source = file_get_contents((string)$this->info);
    	$this->tokens = token_get_all($this->source);
    	$this->count = count($tokens);
	}

	protected function checkMismatch(){
        $name = $this->info->getBasename('.php');
        return (bool)preg_match('`(class|interface)\\s' . $name . '\\W`s', $this->source) == 0);
	}

	/**
	 * Perform analysis on the file
	 * @param \Packfire\PDC\Report\Report $report The report to be generated later
	 * @since 1.0.4
	 */
	public function analyze($report){
        $index = array();
        
		$report->processFile((string)$this->info);
		if(!$this->checkMismatch()){
			$report->increment(ReportType::MISMATCH);
		}
	}

	/**
	 * Get all class names used in the source code
	 * @return array Returns an array of string containing all the class names
	 * @since 1.0.4
	 */
	public function classes(){
	    $classes = array();
	    $nextString = false;
	    for ($current = 0; $current < $this->count; ++$current) {
	        if(is_array($tokens[$current])){
	            $current = $tokens[$current][0];
	            if ($current == T_NEW || $current == T_INSTANCEOF) {
	                $current += 2;
	                $class = $this->fullClass($current);
	                $classes[] = $class;
	            }elseif($current == T_PAAMAYIM_NEKUDOTAYIM){
	                $reset = $current;
	                while($tokens[$current-1][0] == T_NS_SEPARATOR 
	                	|| $tokens[$current-1][0] == T_STRING){
	                    --$current;
	                }
	                $test = $current;
	                $class = $this->fullClass($current);
	                $classes[] = $class;
	                $current = $reset;
	            }elseif($current == T_CATCH){
	                while(++$current < $this->count){
	                    if(is_array($tokens[$current])){
	                        $current = $tokens[$current][0];
	                        if($current == T_STRING
	                         || $current == T_NS_SEPARATOR){
	                            $class = $this->fullClass($current);
	                            $classes[] = $class;
	                            --$current;
	                            break;
	                        }
	                    }
	                }
	            }elseif($current == T_EXTENDS || $current == T_IMPLEMENTS){
	                while(++$current < $this->count){
	                    if(is_array($tokens[$current])){
	                        $current = $tokens[$current][0];
	                        if($current == T_STRING
	                         || $current == T_NS_SEPARATOR){
	                            $class = $this->fullClass($current);
	                            $classes[] = $class;
	                            --$current;
	                        }elseif($current == T_IMPLEMENTS){
	                            --$current;
	                            break;
	                        }
	                    }elseif($tokens[$current] == '{'){
	                        break;
	                    }
	                }
	            }
	        }
	    }
	    return $classes;
	}

	/**
	 * Get the full namespace / classname
	 * @param integer &$start (reference) The start index to read
	 *		 the Namespace/Classname from
	 * @return string Returns the full namespace or classname read
	 * @since 1.0.4
	 */
	protected function fullClass(&$start){
	    $class = '';
	    do{
	        if(is_array($this->tokens[$start])){
	            $current = $this->tokens[$start][0];
	            if($current == T_NS_SEPARATOR || $current == T_STRING 
	            	|| $current == T_VARIABLE || $current == T_STATIC){
	                $class .= $this->tokens[$start][1];
	            }else{
	                break;
	            }
	        }else{
	            break;
	        }
	    }while(++$start < $this->count);
	    return $class;
	}

}