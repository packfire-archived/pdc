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

use Symfony\Component\Process\Process;

/**
 * Helps to provide compilation into a PHAR binary
 * 
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright 2012 Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\PDC
 * @since 1.0.4
 * @link https://github.com/packfire/pdc/
 */

class Compiler {
    
    private $version;
    
    private $phar;
    
    private $strip = true;
    
    private function loadVersion(){
        $process = new Process('git log --pretty="%h" -n1 HEAD', __DIR__);
        if ($process->run() != 0) {
            throw new \RuntimeException('Can\'t run "git log". You must compile from git repository clone and that git binary is installed.');
        }
        $this->version = trim($process->getOutput());

        $processTag = new Process('git describe --tags HEAD');
        if ($processTag->run() == 0) {
            $this->version = trim($processTag->getOutput());
        }
    }
    
    public function compile($pharFile = 'pdc.phar'){
        if(is_file($pharFile)){
            @unlink($pharFile);
        }
        $this->loadVersion();
        $this->phar = new \Phar($pharFile, 0, 'pdc.phar');
        $this->phar->setSignatureAlgorithm(\Phar::SHA1);
        $this->addFile(new \SplFileInfo(__DIR__ . '/../../../license'));
        $this->phar->startBuffering();
        $this->addFolder(__DIR__ . '/../../');
        $this->addFolder(__DIR__ . '/../../../bin/');
        $this->addFolder(__DIR__ . '/../../../vendor/');
        $this->addFolder(__DIR__ . '/../../../test/');
        $this->addFile(new \SplFileInfo(__DIR__ . '/../../../phpunit.xml.dist'));
        $this->phar->setStub($this->stub());
        $this->phar->stopBuffering();
        $this->strip = false;
    }
    
    private function addFolder($folder){
        $iterator = new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator($folder),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );
        foreach($iterator as $path){
            $this->addFile($path);
        }
    }
    
    private function addFile($file) {
        if($file->getRealPath() == __DIR__){
            return;
        }
        $path = str_replace(
                dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR,
                '', $file->getRealPath());
        $content = file_get_contents($file);
        if ($this->strip) {
            $content = $this->stripWhitespace($content);
        }
        if ($file->getBaseName() == 'license') {
            $content = "\n\n$content\n\n";
        }
        
        $content = preg_replace('{^#!/usr/bin/env php\s*}', '', $content);
        $content = str_replace('{{version}}', $this->version, $content);

        $this->phar->addFromString($path, $content);
    }
    
    private function stub(){
        $stub = <<<'EOF'
#!/usr/bin/env php
<?php
/**
 * Packfire Dependency Checker (pdc)
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) 2012, Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * All rights reserved.
 */

Phar::mapPhar('pdc.phar');

EOF;

        // add warning once the phar is older than 30 days
        if (preg_match('{^[a-f0-9]+$}', $this->version)) {
            $stub .= "echo 'Warning: this is not a stable build';\n";
        }

        return $stub . <<<'EOF'
require 'phar://pdc.phar/bin/pdc';

__HALT_COMPILER();
EOF;
    }

    private function stripWhitespace($source){
        if (!function_exists('token_get_all')) {
            return $source;
        }

        $output = '';
        foreach (token_get_all($source) as $token) {
            if (is_string($token)) {
                $output .= $token;
            } elseif (in_array($token[0], array(T_COMMENT, T_DOC_COMMENT))) {
                //$output .= str_repeat("\n", substr_count($token[1], "\n"));
            } elseif (T_WHITESPACE === $token[0]) {
                // reduce wide spaces
                $whitespace = preg_replace(array('{[ \t]+}', '{(\r\n|\r|\n)}', '{\n +}'), array(' ', "\n", "\n"), $token[1]);
                $output .= $whitespace;
            } else {
                $output .= $token[1];
            }
        }

        return $output;
    }
    
}
