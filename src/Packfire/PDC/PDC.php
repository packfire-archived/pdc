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

use Packfire\Options\OptionSet;
use Packfire\PDC\Toolbelt;
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
     * The option set controller
     * @var \Packfire\Command\OptionSet
     * @since 1.0.5
     */
    private $optionSet;

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

    /**
     * Flag whether display help screen or not
     * @var boolean
     * @since 1.0.5
     */
    private $help = true;

    /**
     * Create a new PDC object
     * @param array $args The argument for the console application
     * @since 1.0.4
     */
    public function __construct($args) {
        array_shift($args);
        $this->optionSet = new OptionSet();
        $this->optionSet->add('bootstrap=', array($this, 'setAutoLoader'), 'Set the script to perform bootstrapping and autoloading.');
        $this->optionSet->add('h|help', array($this, 'setHelp'), 'Display PDC help information.');
        $this->optionSet->addIndex(-1, array($this, 'setPath'));
        $this->optionSet->parse($args);
    }

    /**
     * Run the console application
     * @since 1.0.4
     */
    public function run() {
        echo "Packfire Dependency Checker Tool\nWritten by Sam-Mauris Yong v" . PDC::VERSION . "\n\n";

        if($this->help){
            echo "Usage: php pdc.phar [--bootstrap=vendor/autoload.php] source-dir\n\n";
            echo $this->optionSet->help();
        }else{
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
            echo 'Time: ' . Toolbelt::significantFigure($timeTaken, 5) . " seconds\n";
            echo "-- PDC Complete --\n";
        }
    }

    public function setHelp(){
        $this->help = true;
    }

    public function setPath($path) {
        $this->help = false;
        $this->path = $path;
    }

    public function setAutoLoader($autoloader) {
        $this->autoloader = $autoloader;
    }

}