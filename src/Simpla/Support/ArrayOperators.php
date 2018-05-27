<?php

/**
 * Simpla - Just a simple Framework
 *  
 * The MIT License (MIT)
 * For full copyright and license information, please see the LICENSE.txt
 * 
 * @link          https://simplaframework.com
 * @since         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

namespace Simpla\Support;


/**
 * Implement ArrayOperators to use dot notation
 * @package 
 * @subpackage 
 * @author robert <robert.di.jesus@gmail.com>
 * @version 1.0.0
 */
trait ArrayOperators
{
  
    /**
     * @param array $array
     * @param string $key
     *
     * @return bool
     */
    protected function arrayExists(array $array, $key)
    {  
        $keys = (array) $key;
        
        if(!$this->data || $key === []){
            return false;
        }
         
        foreach ($keys as $key) { 
            $arr = $array;
            if (isset($array[$key])) {
                continue;
            }

            foreach (explode(".", $key) as $part) {
                if (!is_array($arr) or !isset($arr[$part])) {
                    return false;
                }

              $arr = $arr[$part];

            }
        }
  
        return true;

    }      
    
    /**
     * @param array $array
     * @param string $key
     *
     * @return null
     */
    protected function getArray(array $array, $key, $getKey=false)
    {
        if(!is_array($key)){            
            if(isset($array[$key])) {
                return $array[$key];
            }

            foreach (explode(".", $key) as $part) {
                if (!is_array($array) or !isset($array[$part])) {
                    return null;
                }

                $array = $array[$part];
            }
        }
        return ($getKey) ? [$part => $array] : $array;
    }    
    
    /**
     * @param array $array
     * @param string $key
     * @param mixed $value
     */
    protected function setArray(array &$array, $key, $val, $replace = null)
    { 
        $parts = $this->replaceValue( explode(".", $key), $replace);    
        $value = $this->replaceValue($val, $replace);    
       
        while (count($parts) > 1) {
            $part = array_shift($parts);
            
            if($part === ""){
                $array =& $array[]; 
                continue;
            }
              
            if (!isset($array[$part]) or !is_array($array[$part])) {
                $array[$part] = [];
            }
             
            $array =& $array[$part];
        }
        
        $lastKey = array_shift($parts);
        
        if($lastKey === ""){
            $array[] = $value;
        }
        else{
            $array[$lastKey] = $value;
        }
    }  
      
    private function replaceValue($param, $replace = null)
    {  
        if(is_null($replace)){
            return $param;
        }
        
        if(!is_array($param)){
            if(is_int(strpos($param, $replace))){
                return str_replace($replace, ".", $param);
            }        
        }
         
        foreach ((array)$param as $key => $value) { 
            if(is_int(strpos($value, $replace))){ 
                $param[$key] =  str_replace($replace, ".", $value); 
            } 
        }
        
        return $param;
    }
    
    /**
     * @param array $array
     * @param string $key
     * @param mixed $value
     */
    protected function addStartArray(array &$array, $key, $value)
    {
        $parts = explode(".", $key);
    
        while(count($parts) > 1){
            $part  = array_shift($parts);
              
            if (!isset($array[$part]) or !is_array($array[$part])) {
                $array = array_reverse($array, true);                
                $array[$part] = [];
                $array = array_reverse($array, true);
            }

            $array =& $array[$part];
        }
          
         
       $array = array_merge([array_shift($parts) => $value], $array); 
    }
    
    
    /**
     * @param array $array
     * @param string $key
     * @param mixed $value
     */
    protected function insertArray(array &$array, $key, $value, $position)
    {
        $parts = explode(".", $key);
    
        while(count($parts) > 1){
            $part = array_shift($parts);              
            $array =& $array[$part];
        }
          
        if (!is_int($position)) {  
            $position  = array_search($position, array_keys($array));               
        }   
        
        $array = array_merge(
            array_slice($array, 0, $position),
            [array_shift($parts) => $value],
            array_slice($array, $position)
        );   
    } 
    
    
    /**
     * @param array $array
     * @param string $key
     */
    protected function unsetArray(array &$array, $key)
    {
      $parts = explode(".", $key);

      while (count($parts) > 1) {
        $part = array_shift($parts);

        if (isset($array[$part]) and is_array($array[$part])) {
          $array =& $array[$part];
        }
      } 
      
      unset($array[array_shift($parts)]);
    }    
        

}
