<?php

/**
 * Packfire Dependency Checker (pdc)
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\PDC;

/**
 * Utility Toolbelt for the package
 * 
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright Sam-Mauris Yong <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\PDC
 * @since 1.0.4
 * @link https://github.com/packfire/pdc/
 */
class Toolbelt {

    /**
     * Get the class name from the entire namespace
     * @param string $namespace The full namespace to extract the class name
     * @return string Returns the class name extracted from the namespace
     * @since 1.0.4
     */
    public static function classFromNamespace($namespace){
        return ltrim(substr($namespace, strrpos($namespace, '\\')), '\\');
    }

    /**
     * Round a number to a certain significant number
     * @param integer|double $value The value to round
     * @param integer $sigFigs The number of significant numbers
     * @return double Returns the number rounded
     * @since 1.0.4
     */
    public static function significantFigure($value, $sigFigs = 3){
        //convert to scientific notation e.g. 12345 -> 1.2345x10^4
        //where $significand is 1.2345 and $exponent is 4
        $exponent = floor(log10(abs($value))+1);
        $significand = round(($value
            / pow(10, $exponent))
            * pow(10, $sigFigs))
            / pow(10, $sigFigs);
        return $significand * pow(10, $exponent);
    }

}