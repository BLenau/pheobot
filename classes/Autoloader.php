<?php
/**
 * This file contains the class responsible for autoloading other classes.
 * 
 * Copyright (C) 2014 Brian M. Lenau
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @license http://www.gnu.org/licenses/
 * 
 * @author Brian M. Lenau <blenau@gmail.com>
 */

/**
 * A class whose sole purpose is to expose a method to use for autoloading all
 * classes.
 */
class Autoloader {
    
    /**
     * Allows a class to be autoloaded if it exists.
     * 
     * @param string $classname The name of the class to load
     */
    public static function load($classname) {
        $class = __DIR__ . "/" . str_replace("\\", "/", $classname) . ".php";
        if (file_exists($class)) {
            return require($class);
        }
        throw new Exception("File: <$classname> not found.");
    }
}
?>