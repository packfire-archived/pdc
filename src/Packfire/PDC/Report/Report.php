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
    private $files = array();
    private $currentFile;

    public function add($key, $index) {
        if ($index instanceof Index) {
            $this->indexes[$key] = $index;
        } else {
            throw new \InvalidArgumentException('Report::add() expected $index to be an object of \Packfire\PDC\Report\Index.');
        }
    }

    public function processFile($file) {
        $this->currentFile = $file;
        $this->files[$this->currentFile] = array();
    }

    public function increment($key, $details = null) {
        if (isset($this->indexes[$key])) {
            $index = $this->indexes[$key];
            $index->increment();
            if ($this->currentFile) {
                $this->files[$this->currentFile][] = $index->message() . ($details ? ': ' . $details : '');
            }
        } else {
            throw new \Exception($key . ' index not found in report.');
        }
    }

    public function report() {
        $buffer = '';
        foreach ($this->files as $file => $details) {
            $details = array_filter($details);
            if(count($details) > 0){
                $buffer .= "[$file]:\n";
                foreach ($details as $text) {
                    $buffer .= "$text\n";
                }
                $buffer .= "\n";
            }
        }

        $buffer .= "-Summary-\n";
        foreach ($this->indexes as $index) {
            if ($index->count() > 0) {
                $buffer .= $index->summary() . "\n";
            }
        }
        $buffer .= "\n";

        return $buffer;
    }

}