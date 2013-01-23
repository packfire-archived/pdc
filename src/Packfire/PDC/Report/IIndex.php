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
 * A Report Index Interface
 * 
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright Sam-Mauris Yong <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\PDC\Report
 * @since 1.0.8
 * @link https://github.com/packfire/pdc/
 */
interface IIndex extends \Countable {
    
    
    /**
     * Create a new Index object
     * @param string $summary The summary string to display at summary
     * @param string $message (optional) Detailed message of the index
     * @since 1.0.8
     */
    public function __construct($summary, $message = null);

    /**
     * Add to the counter
     * @since 1.0.8
     */
    public function increment();

    /**
     * Get the formatted summary
     * @return string Returns the summary
     * @since 1.0.8
     */
    public function summary();

    /**
     * Get the message string
     * @return string Returns the message string
     * @since 1.0.8
     */
    public function message();
    
}