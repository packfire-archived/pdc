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
use Packfire\PDC\Report\Report;
use Packfire\PDC\Report\ReportType;
use Packfire\PDC\Report\Index as ReportIndex;

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
     * Version of PDC
     */
    const VERSION = '{{version}}';

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

    public function __construct($args) {
        $optionSet = new OptionSet($args);
        $optionSet->addIndex(2, array($this, 'setAutoLoader'));
        $optionSet->process();
    }

    public function run() {
        echo "Packfire Dependency Checker Tool\nWritten by Sam-Mauris Yong v" . PDC::VERSION . "\n\n";

        $startTime = microtime(true);
        $iterator = new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator($this->path),
                        \RecursiveIteratorIterator::CHILD_FIRST);
        if ($this->autoloader) {
            require $this->autoloader;
        } elseif (is_file('vendor/autoload.php')) { // autodetect composer's autoloader
            include('vendor/autoload.php');
        }

        $report = new Report();
        $report->add(ReportType::FILE, new ReportIndex('%d files checked'));
        $report->add(ReportType::NO_NAMESPACE, new ReportIndex('%d files with no namespace declaration', 'No namespace found'));
        $report->add(ReportType::MISMATCH, new ReportIndex('%d file and class name mismatch', 'File and class name mismatch'));
        $report->add(ReportType::NOT_FOUND, new ReportIndex('%d dependencies not found', 'Not found'));
        $report->add(ReportType::UNUSED, new ReportIndex('%d usused dependncies found', 'Unused'));

        foreach ($iterator as $path) {
            $extension = pathinfo($path->getFilename(), PATHINFO_EXTENSION);
            if ($path->isFile() && $extension == 'php') {
                $analyzer = new Analyzer($path);
                $analyzer->analyze($report);
            }
        }

        echo $report->report();
        $timeTaken = microtime(true) - $startTime;
        echo 'Time: ' . sigFig($timeTaken, 5) . " seconds\n";
        echo "-- PDC Complete --\n";
    }

    public function setPath($path) {
        $this->path = $path;
    }

    public function setAutoLoader($autoloader) {
        $this->autoloader = $autoloader;
    }

}