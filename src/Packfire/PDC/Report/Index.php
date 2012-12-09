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
class Index implements \Countable {

    /**
     * The counter of the index
     * @var integer
     * @since 1.0.4
     */
    private $counter = 0;

    /**
     * The summary text to be displayed at the summary
     * '%d' denotes where the counter number will appear
     * @var string
     * @since 1.0.4
     */
    private $summary;

    /**
     * Message to show whenever an instance occur
     * @var string
     * @since 1.0.4
     */
    private $message;

    /**
     * Create a new Index object
     * @param string $summary The summary string to display at summary
     * @param string $message (optional) Detailed message of the index
     * @since 1.0.4
     */
    public function __construct($summary, $message = null){
        $this->summary = $summary;
        $this->message = $message;
    }

    /**
     * Add to the counter
     * @since 1.0.4
     */
    public function increment(){
        ++$this->counter;
    }

    /**
     * Get the formatted summary
     * @return string Returns the summary
     * @since 1.0.4
     */
    public function summary(){
        return sprintf($this->summary, $this->counter);
    }

    /**
     * Get the message string
     * @return string Returns the message string
     * @since 1.0.4
     */
    public function message(){
        return $this->message;
    }

    /**
     * Get the index counter
     * @return integer Returns the counter
     * @since 1.0.4
     */
    public function count(){
        return $this->counter;
    }
	
}