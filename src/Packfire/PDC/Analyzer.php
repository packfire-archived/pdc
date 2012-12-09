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
    public function __construct($file) {
        if ($file instanceof \SplFileInfo) {
            $this->info = $file;
        } else {
            $this->info = new \SplFileInfo($file);
        }
        $this->source = file_get_contents((string) $this->info);
        $this->tokens = token_get_all($this->source);
        $this->count = count($tokens);
    }

    protected function checkMismatch($name) {
        return (bool) preg_match('`(class|interface)\\s' . $name . '\\W`s', $this->source) == 0;
    }

    protected function fetchNamespace() {
        $namespace = '';
        if (preg_match('`namespace\\s(?<namespace>[a-zA-Z\\\\]+);`s', $this->source, $namespace)) {
            $namespace = $namespace['namespace'];
        } else {
            $namespace = '';
        }
        return $namespace;
    }

    /**
     * Perform analysis on the file
     * @param \Packfire\PDC\Report\Report $report The report to be generated later
     * @since 1.0.4
     */
    public function analyze($report) {

        $report->processFile((string) $this->info);
        $className = $this->info->getBasename('.php');
        if (!$this->checkMismatch($className)) {
            $report->increment(ReportType::MISMATCH, $className);
        }

        $namespace = $this->fetchNamespace();
        if (!$namespace) {
            $report->increment(ReportType::NO_NAMESPACE);
        }

        $index = $this->useIndexing();
    }

    protected function useIndexing() {
        $index = array();
        preg_match_all('`use\\s(?<namespace>[a-zA-Z\\\\]+)(\\sas\\s(?<alias>[a-zA-Z]+)|);`s', $contents, $uses, PREG_SET_ORDER);
        foreach ($uses as $use) {
            if (isset($use['alias'])) {
                $index[$use['alias']] = $use['namespace'];
            } else {
                if (false !== $pos = strrpos($use['namespace'], '\\')) {
                    $alias = substr($use['namespace'], $pos + 1);
                } else {
                    $alias = $use['namespace'];
                    $index[Toolbelt::classFromNamespace($alias)] = $alias;
                }
                $index[$alias] = $use['namespace'];
            }
        }
        return $index;
    }

    /**
     * Get all class names used in the source code
     * @return array Returns an array of string containing all the class names
     * @since 1.0.4
     */
    public function classes() {
        $classes = array();
        for ($current = 0; $current < $this->count; ++$current) {
            if (is_array($this->tokens[$current])) {
                $current = $this->tokens[$current][0];
                if ($current == T_NEW || $current == T_INSTANCEOF) {
                    $current += 2;
                    $class = $this->fullClass($current);
                    $classes[] = $class;
                } elseif ($current == T_PAAMAYIM_NEKUDOTAYIM) {
                    $reset = $current;
                    while ($this->tokens[$current - 1][0] == T_NS_SEPARATOR
                    || $this->tokens[$current - 1][0] == T_STRING) {
                        --$current;
                    }
                    $class = $this->fullClass($current);
                    $classes[] = $class;
                    $current = $reset;
                } elseif ($current == T_CATCH) {
                    while (++$current < $this->count) {
                        if (is_array($this->tokens[$current])) {
                            $current = $this->tokens[$current][0];
                            if ($current == T_STRING
                                    || $current == T_NS_SEPARATOR) {
                                $class = $this->fullClass($current);
                                $classes[] = $class;
                                --$current;
                                break;
                            }
                        }
                    }
                } elseif ($current == T_EXTENDS || $current == T_IMPLEMENTS) {
                    while (++$current < $this->count) {
                        if (is_array($this->tokens[$current])) {
                            $current = $this->tokens[$current][0];
                            if ($current == T_STRING
                                    || $current == T_NS_SEPARATOR) {
                                $class = $this->fullClass($current);
                                $classes[] = $class;
                                --$current;
                            } elseif ($current == T_IMPLEMENTS) {
                                --$current;
                                break;
                            }
                        } elseif ($this->tokens[$current] == '{') {
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
     * 		 the Namespace/Classname from
     * @return string Returns the full namespace or classname read
     * @since 1.0.4
     */
    protected function fullClass(&$start) {
        $class = '';
        do {
            if (is_array($this->tokens[$start])) {
                $current = $this->tokens[$start][0];
                if ($current == T_NS_SEPARATOR || $current == T_STRING
                        || $current == T_VARIABLE || $current == T_STATIC) {
                    $class .= $this->tokens[$start][1];
                } else {
                    break;
                }
            } else {
                break;
            }
        } while (++$start < $this->count);
        return $class;
    }

}