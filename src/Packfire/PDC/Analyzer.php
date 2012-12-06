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
	 * @param string $source The source code to analyze
	 * @since 1.0.4
	 */
	public function __construct($source){
    	$this->tokens = token_get_all($php_code);
    	$this->count = count($tokens);
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
	private function fullClass(&$start){
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