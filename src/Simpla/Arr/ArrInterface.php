<?php

/**
 * Simpla - Just a simple Framework
 *  
 * The MIT License (MIT)
 * For full copyright and license information, please see the LICENSE.txt
 *
 *
 * @link          https://simplaframework.com
 * @since         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

namespace Simpla\Arr;


/**
 * Description of ArrInterface
 * @package 
 * @subpackage 
 * @author robert <robert.di.jesus@gmail.com>
 * @version 1.0.0
 */
interface ArrInterface
{
    /**
     * Show All Array
     * 
     * @return array
     */
    public function all();
    
    /**
     * Delete the contents 
     * 
     * @param mixed|null $key
     */
    public function clear($key = null);
    
    /**
     * Count contents of the key or all array
     * 
     * @param mixed|null $key
     * @return integer
     */
    public function count($key = null);
    
    /**
     * Delete the given key or keys
     * 
     * @param mixed|null $key
     */
    public function delete($key = null);
    
    /**
     * Return the value of a given key
     * 
     * @param mixed|null $key
     * @return mixed
     */
    public function get($key = null);
    
    /**
     * Check if a given key or keys exists
     * 
     * @param mixed $key
     */
    public function has($key);
    
    /**
     *  Check if a given key or keys are empty
     * 
     * @param mixed|null $key
     */
    public function isEmpty($key = null);
     
    /**
     * Add an item at the beginning of the array
     * 
     * @param mixed $key
     * @param mixed $value
     */
    public function prepend($key, $value);
    
    /**
     * Push a given value to the end of the array in a given key
     * 
     * @param string $key 
     * @param mixed $value
     */
    public function add($key, $value);
        
    /**
     * Push a given value to the end of the array in a given key
     * 
     * @param mixed $key
     * @param midex $value
     */
    public function push($key, $value);
    
    /**
     * Return the value of a given key and delete the key 
     * 
     * @param mixed|null $key
     * @param bool $preserveKey Defines whether the original array key must be preserved in the result
     * @return mixed 
     */
    public function shift($key = null, bool $preserveKey = false) ; 
    
    /**
     * Return the value of a given key and delete the key 
     * 
     * @param mixed|null $key
     * @param bool $preserveKey Defines whether the original array key must be preserved in the result
     * @return mixed 
     */
    public function pull($key = null, bool $preserveKey = false);
    
    /**
     * Return the value of a given key and delete the key 
     * 
     * @param mixed|null $key
     * @param bool $preserveKey Defines whether the original array key must be preserved in the result
     * @return mixed 
     */
    public function pop($key = null, bool $preserveKey = false); 
    
    /**
     * Inserts an item at a given position of the array
     * 
     * @param mixed $key
     * @param mixed $value
     * @param mixed $position
     */
    public function insert($key, $value, $position);
    
    /**
     * return the first value to array
     * 
     * @param mixed|null $key
     * @return mixed
     */
    public function first($key = null);
    
    /**
     * return the end value to array
     * 
     * @param mixed|null $key
     * @return mixed
     */
    public function last($key = null);
     
    /**
     * Returns the first key of an array
     * 
     * @access public
     * @param array $array
     * @return array
     * 
     */
    public function firstKey($array = null);
    
    /**
     * Returns the last key of the array
     * 
     * @access public
     * @static
     */
    public function lastKey($array = null);
    
    
    /**
     * 
     * @param mixed|null $key
     * @return string
     */
    public function toJson($key = null);
}
