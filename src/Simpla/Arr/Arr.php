<?php

/**
 * Simpla - Just a simple Framework
 *  
 * The MIT License (MIT)
 * For full copyright and license information, please see the LICENSE.txt
 *
 * Falta Implementar:
 *    multi_to_single 
 *    in_array_recursive 
 *    array_simplify 
 *    array_flatten
 *    array_intersect_by_key 
 *    array_intersect_key_recursive 
 *    array_diff_by_key
 *    change_key 
 *    object_to_array 
 *    array_unique_recursive 
 *    array_diff_key_recursive
 *    recursive_unset 
 *    array_search_recursive 
 *    array_pluck
 *    
 *
 * @link          https://simplaframework.com
 * @since         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Simpla\Arr;
 
use Countable;
use ArrayAccess;
use ArrayIterator;
use JsonSerializable;
use IteratorAggregate;
use Simpla\Arr\ArrInterface;
use Simpla\Support\ArrayOperators;
use Simpla\Support\ArrayAccessTrait;


class Arr implements ArrayAccess, Countable, JsonSerializable, IteratorAggregate, ArrInterface
{
    use ArrayOperators;
    use ArrayAccessTrait;
    
    const ARR_PRESERVE_KEY = true;
    const SORT_REVERSE = 2;
    
    private $replace = "~~~~";
     
    public function __construct($array = [])
    {
        $this->data = $array;
    }
    
    /**
     * Create a new collection instance if the value isn't one already.
     *
     * @param  mixed $items
     *
     * @return Arr
     */
    public static function make($items = [])
    {
        return new static($items);
    }        
    
    
    private function getDefinedArray($array)
    {  
        if(!is_null($array)){            
            if(!is_array($array) && $this->has($array)){  
                return $this->offsetGet($array);
            }
            
            return false;
        }
        
        return $this->data; 
    }
    

    private function &getArrayReference($array)
    {
        if(!is_null($array)){            
            if(!is_array($array) && $this->has($array)){   
                $_array =& $this->offsetGet($array); 
            } 
        }
        else{
            $_array =& $this->data;        
        }

        return $_array;
    }   
    
    /***************************************************************************
     *                            Get Array (info)
     ***************************************************************************/
    
//    
//    public function define(array $data = [])
//    {
//      $this->data = $data;
//    }
//     
//    
    
    public function set(array $array)
    {
        if(!is_array($array)){
            $this->add("", $array);
            
            return $this;
        }
        
        if($this->isMulti($array)){
           $this->data = array_merge_recursive($this->data, $array);
            
            return $this;
        }
        
        foreach ($array as $key => $dotNotation) {
            if(!is_int(strpos($dotNotation, "."))){
                $this->offsetSet($key, $dotNotation);                
                continue;
            }

            preg_match_all("/([\'](.*?)[\'])/", $dotNotation, $matches);

            $newDot = ($get = $this->setNumericValueswithDot($matches, $dotNotation)) ? $get : $dotNotation;
            $dot = explode(".", $newDot);
            $value = array_pop($dot);
            
            $this->offsetSet(implode(".", $dot), $value, $this->replace);
        }
         
        
        return $this;
    }
    
    private function setNumericValueswithDot($matches, $dot)
    {
        $num = explode("'", $dot);
        
        if(empty($matches[0])){
            return false;
        }
        
        foreach ($matches[0] as $match) {
            foreach ($num as $key => $value) {
                $tmp = trim($match,"'");
                if($value == $tmp){
                    $num[$key] = str_replace(".", $this->replace, $value); 
                }
            } 
        } 
        
        return implode("", $num);
    }    
    
    /**
     * {@inheritDoc}
     */
    public function get($key = null, $getKey = false)
    {
        if(is_null($key)){
            return $this->data;
        }
        
        return ($this->offsetExists($key)) ? $this->offsetGet($key, $getKey) : null;
    }       
    
    /**
     * {@inheritDoc}
     */
    public function all() 
    {
        return $this->get();
    }
    
    /**
     * {@inheritDoc}
     */
    public function first($key = null)
    {
        $_array = $this->getDefinedArray($key);
                
        
        if(!is_array($_array)){
            return $_array;
        }        
          
        return reset($_array);  
    }
     
    /**
     * {@inheritDoc}
     */
    public function last($key = null)
    {
        $_array = $this->getDefinedArray($key);
          
        if(!is_array($_array)){
            return $_array;
        }        
          
        return end($_array);
    } 
      
    /**
     * {@inheritDoc}
     */
    public function firstKey($array = null)
    {
        $_array = $this->getDefinedArray($array);
                
        if(!is_array($_array)){
            return false;
        }
        
        reset($_array);
        return key($_array);
    }
     
    /**
     * {@inheritDoc}
     */
    public function lastKey($array = null)
    {
        $_array = $this->getDefinedArray($array);
                
        if(!is_array($_array)){
            return false;
        }
        
        end($_array); 
        return key($_array);
    }      
    
         
    /**
     * {@inheritDoc}
     */
    public function toJson($key = null, $options = 0) 
    {
        if(is_string($key)){
            return json_encode($this->get($key), $options);
        }
        
        $options = $key === null ? 0 : $key;
        
        return json_encode($this->jsonSerialize(), $options);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    public function jsonSerialize()
    {
        return $this->data;
    }
            
    /**
     * Retornar a quantidade de dimensões de um array
     * 
     * @param mixed|null $array
     * @return int Quantidade de dimensões
     */
    public function getMaxDimensions($array = null)
    {
        $_array = $this->getDefinedArray($array);
                
        if(!is_array($_array)){
            return false;
        }
        
        return $this->countDimmensions($_array);
    } 
    
    
    private function countDimmensions($array)
    {
        $dimensions = 1;
        $max = 0;
        foreach ($array as $value) {
            if (is_array($value)) {
                $subDimensions = $this->countDimmensions($value);
                if ($subDimensions > $max) {
                    $max = $subDimensions;
                }
            }
        }

        return $dimensions+$max;        
    }
    
    /***************************************************************************
     *                            Array Operators (info)
     ***************************************************************************/
    
    /**
     * {@inheritDoc}
     */
    public function count($key = null) 
    {
        return count($this->get($key));
    }
  
    /**
     * {@inheritDoc}
     */
    public function delete($key = null)
    {
        if(is_null($key)){
            unset($this->data);
            
            return ;
        }
        
        $this->offsetUnset($key);
    }
    
    /**
     * {@inheritDoc}
     */
    public function clear($key = null)
    {
        if(is_null($key)){
            $this->data = [];
            
            return ;
        } 
        
        foreach ((array) $key as $k){ 
            $this->add($k, []);
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function prepend($key, $value)
    {
        $this->addStartArray($this->data, $key, $value); 
    }
    
    /**
     * {@inheritDoc}
     */
    public function add($key, $value)
    {
        if(is_null($value)){
            $this->data[] = $key;
            
            return ;
        }
         
//        $item = $this->get($key);
//        
//        if(is_array($item) || is_null($item)){
//            $item[] = $value;
//            $this->offsetSet($key, $value);
//        }           
        
        $this->offsetSet($key, $value); 
    }
    
    /**
     * {@inheritDoc}
     */
    public function push($key, $value)
    {
        $this->add($key, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function pull($key = null, bool $preserveKey = false)
    {        
        if(is_bool($key)){ 
            $preserveKey = $key;
            $key = null;
        }
        
        $value = $this->first($key);
        $keyPop = $this->firstKey($key);
        $keyNull = (is_null($key)) ? "" : $key;
        $finalKey = !$keyPop ? $keyNull : $keyNull.'.'.$keyPop;
         
         
        $this->delete($finalKey);
        
        return ($preserveKey == self::ARR_PRESERVE_KEY) ? [($keyPop ? $keyPop : $keyNull) => $value] : $value; 
    }
    
    /**
     * {@inheritDoc}
     */
    public function shift($key = null, bool $preserveKey = false)
    {
        return $this->pull($key, $preserveKey);
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function pop($key = null, bool $preserveKey = false)
    {
        if(is_bool($key)){
            $preserveKey = $key;
            $key = null;
        }
        
        $value = $this->last($key);
        $keyPop = $this->lastKey($key);
        $keyNull = (is_null($key)) ? "" : $key;
        $finalKey = !$keyPop ? $keyNull : $keyNull.'.'.$keyPop;
        
        $this->delete($finalKey);
          
        return ($preserveKey == self::ARR_PRESERVE_KEY) ? [($keyPop ? $keyPop : $keyNull)  => $value] : $value; 
    }    
     
    /**
     * {@inheritDoc}
     */
    public function insert($key, $value, $position)
    {
        $this->insertArray($this->data, $key, $value, $position);
        
        return $this;
    }
    
    /***************************************************************************
     *                            Check the Array
     ***************************************************************************/
     
    /**
     * {@inheritDoc}
     */
    public function has($key)
    {
        return $this->offsetExists($key);
    }
    
    /**
     * {@inheritDoc}
     */
    public function isEmpty($key = null)
    {
        if(is_null($key)){
            return empty($this->data);
        }
        
        foreach ((array)$key as $k) {
            if(!empty($this->get($key))){
                return false;
            }
        }
        
        return true;
    }
      
    
    /**
     * Check if a array is associative.
     * 
     * @access public 
     * @param array $array Array to be checked
     * @return bool result
     */   
    public function isAssoc($array = null)
    {
        $_array = $this->getDefinedArray($array);
        
        print_r($_array);
                
        if(!is_array($_array)){
            return false;
        }

        $counter = 0;
        foreach ($_array as $key => $unused){
                if ( ! is_int($key) or $key !== $counter++){
                        return true;
                }
        }
        return false;	  
    }
    
    /**
     * Check if a array is multidimensional.
     * 
     * @access public 
     * @param array $array Array to be checked
     * @return bool result
     */
    public function isMulti($array = null)
    {
        $_array = $this->getDefinedArray($array);
                
        if(!is_array($_array)){
            return false;
        }
 
        $values = array_filter($_array, 'is_array');
        return $allKeys ? count($_array) === count($values) : count($values) > 0;
    }  
    
    /**
     * Check if a array is indexed (numeric).
     * 
     * @access public 
     * @param array $array Array to be checked
     * @return bool result
     */      
    public function isIndexed($array)
    {        
        return !$this->isAssoc($array);
    }      
    
    
    /***************************************************************************
     *                            Search the Array
     ***************************************************************************/
          
    /**
     * Find recursively a value in a multidimensional array
     *  
     * @param mixed $search
     * @param mixed $array
     * 
     * @return bool
     */ 
    public function inArray($search, $array = null) 
    { 
        $_array = $this->getDefinedArray($array);
                
        if(!is_array($_array)){
            return false;
        }
        
        return $this->inArr($_array, $search);
    }  
    
    private function inArr($array, $search)
    {
        foreach ($array as  $item) {
            if(($item == $search) || (is_array($item) && $this->inArr($item, $search))){
                return true;
            }
        }
        
        return false;
    }
     
    /**
     * Search recursively for an element within the array by the key
     *  
     * @param array $array Array where search occurs
     * @param mixed $needle Value sought
     * @param boolean $preserveKey Return the key + value set if true
     * @return mixed
     */
    public function findKey($array, $needle = null, $preserveKey=false)
    {
        if(func_num_args()==1 || (func_num_args()==2 && is_bool($needle))){        
            $preserveKey = $needle;
            $needle = $array;
            $array = null;
        }
        elseif($array === ""){
            $array = null;
        }
        
        $array = ($array === "") ? null : $array; 
        $_array = $this->getDefinedArray($array);
                
        if(!is_array($_array)){
            return false;
        }         
        
       $iterator  = new \RecursiveArrayIterator($_array);
       $recursive = new \RecursiveIteratorIterator($iterator,
                            \RecursiveIteratorIterator::SELF_FIRST);
       foreach ($recursive as $key => $value) {
           if ($key === $needle) {
               $res[$key] = $value;
               return ($preserveKey !== self::ARR_PRESERVE_KEY) ? $res[$key] : $res;
           }
       }
    }      
    
    
    
    /***************************************************************************
     *                        Order the Array
     ***************************************************************************/
         
    /**
     * Ordena um array pelas chaves baseadas em um outro array
     *  
     * @param mixed $array Endereco com dot.notation do array, ou informe diretamente o array de ordenacao
     * @param array $orderArray Array contendo as chaves de ordenação
     * @param string $typeOrder Inverte a ordenação
     */
    public function orderByKey($array = null, array $orderArray = null, $typeOrder = null)
    {
        if(is_array($array)){
            $orderArray = $array;
            $array = null;
            $typeOrder = $orderArray;
        }
        elseif($array === null){
            $array = null;
        } 
        
        $_array = &$this->getArrayReference($array);
        $ordered = array();
        $temp = '';
          
        if(!is_array($orderArray)){
            $temp = explode(',', $orderArray);
            $orderArray = $temp;
        }
             
        foreach($orderArray as $key) {
            if(array_key_exists(trim($key),$_array)) {
                    $ordered[trim($key)] = $_array[trim($key)];
                    unset($_array[trim($key)]);
            }
        }           
        
        if(is_null($typeOrder)){
            ksort($_array);
            $result = $ordered + $_array;
        }
        elseif($typeOrder == self::SORT_REVERSE){ 
            krsort($_array);
            $result = $_array + array_reverse($ordered); 
        } 
         
        if(is_null($array)){
            self::make($result);
        }
        else{
            $this->add($array, $result);
        } 
        
        return $this->get($array);
    }
    
    
    /***************************************************************************
     *                        Transform the Array
     ***************************************************************************/
    
    
    public function keyCaseToUpper($array = null)
    {
        $_array = $this->getDefinedArray($array);
                
        if(!is_array($_array)){
            return false;
        }   
           
        return ($array==null) 
            ? $this->arrayChangeKeyCaserecursive($_array, CASE_UPPER) 
            : array_change_key_case($_array, CASE_UPPER);
    }
    
    public function keyCaseToLower($array = null)
    {
        $_array = $this->getDefinedArray($array);
                
        if(!is_array($_array)){
            return false;
        }   
         
        return ($array==null) 
            ? $this->arrayChangeKeyCaserecursive($_array, CASE_LOWER) 
            : array_change_key_case($_array, CASE_LOWER);
    }
     
    private function arrayChangeKeyCaserecursive($arr, $case=CASE_LOWER)
    {
      return array_map(function($item)use($case){
        if(is_array($item))
            $item = $this->arrayChangeKeyCaserecursive($item, $case);
        return $item;
      },array_change_key_case($arr, $case));
    }    
    
    
    
    /**
     * Apply a function on all elements of an array given the key.       
     * 
     * @access public 
     * @link http://php.net/manual/pt_BR/function.array-map.php See array_map function
     * @param Array $array array to be changed
     * @param Closure $callback Function to be applied
     * @return array result 
     */
    public function mapKey($array, \Closure $callback = null)
    {
        if($array instanceof \Closure){
            $callback = $array;            
            $array = null;
        }
        
        $_array = $this->getDefinedArray($array);
                
        if(!is_array($_array)){
            return false;
        }   
        
        $output = []; 
        
        foreach($_array as $key => $value){
            array_push($output, $callback($key, $value));
        }
        
        return $output;
    }       
 
     
    /***************************************************************************
     *                        Special Operation of the Array
     ***************************************************************************/
     
    /**
     * Convert non-associative array to associative array if it has an even number of segments.
     * 
     * @access public 
     * @category Helper
     * @param Array $array Array to be checked
     * @return bool result
     */
    public function toAssoc($array = null)
    {
        $_array = $this->getDefinedArray($array);
          
	if (($count = count($_array)) % 2 > 0){
            throw  new \BadMethodCallException("the number of values in the array must be even");
        }
     
        $keys = $vals = [];
         
        for ($i = 0; $i < $count - 1; $i += 2){   
            $keys[] = array_shift($_array);
            $vals[] = array_shift($_array);        
        }
        
        return array_combine($keys, $vals);
    }    
    
    
    /**
     * Convert associative array to no-associative array if it has an even number of segments.
     * 
     * @access public
     * @static
     * @category Helper
     * @param Array $array Array to be checked
     * @return bool result
     */
    public function assocToKey($assoc, $keyField, $valField)
    {
        if(func_num_args()<3){
            $valField = $keyField;
            $keyField = $assoc;
            $assoc = null;
        }
        
        $_array = $this->getDefinedArray($assoc);
          
        if ( ! is_array($_array) and ! $_array instanceof \Iterator){
            throw new \InvalidArgumentException('The first parameter must be an array.');
        }

        $output = array();
        foreach ($_array as $row){
            if (isset($row[$keyField]) and isset($row[$valField])){
                $output[$row[$keyField]] = $row[$valField];
            }
        } 
        return $output; 
    }    
    
    /**
     * Transforma um array com key=>val em um array multidimensional associativo com os nomes dos campos fornecidos.
     * 
     * @access public 
     * @category Helper
     * @param array $array array a ser transformado
     * @param mixed $keyField O campo do array associativa para mapear como a chave.
     * @param mixed $valField O campo do array associativa para mapear como o valor.
     */    
    public function keyToAssoc($assoc, $keyField, $valField)
    {
        if(func_num_args()<3){
            $valField = $keyField;
            $keyField = $assoc;
            $assoc = null;
        }
        
        $_array = $this->getDefinedArray($assoc);
                  
        if ( ! is_array($_array) and ! $_array instanceof \Iterator)
        {
                throw new \InvalidArgumentException('The first parameter must be an array.');
        }

        $output = array();
        foreach ($_array as $key => $value)
        {
                $output[] = array(
                        $keyField => $key,
                        $valField => $value,
                );
        }

        return $output;
    }    
         
     
    /**
     * Inverte as chaves de um array multidimensional associativo 
     * 
     * @param array $_array Array associativo a ser invertido
     * @param array $array_names Valores alternativos para as chaves. Se não definido serão utilizados os valores associativos originais.
     * @return array retorna uma array associativo <p> Array Original:
     * </p>
     * <pre>
        Array( 
     *          [name] => Array(
                        [0] => 2006-1diemersonmarciotessarovf.pdf
                        [1] => 2014-07-12.jpg
                    )
                [type] => Array( 
                        [0] => adobe/pdf
                        [1] => image/jpeg
                    )
                )     
     * </pre>
     * <p>Array convertido: </p>
     * <pre> 
     * Array(
            [1] => Array
                (
                    [name] => 2006-1diemersonmarciotessarovf.pdf
                    [type] => adobe/pdf 
                )

            [2] => Array
                (
                    [name] => 2014-07-12.jpg
                    [type] => image/jpeg 
                )
     * )
     * </pre>
     * 
     */
    public function reverseAssoc($reverse = null, $array_names=[])
    { 
        if(func_num_args()==1){
           if(is_array($reverse)){
                $array_names = $reverse;
                $reverse = null;
           }
        }
        
        $_array = $this->getDefinedArray($reverse);
                  
        
        $associative =  array_keys($_array);
        $totalAssoc = count($associative);
        $totalElements = count($_array[$associative[0]]);   
        
        $nameAssoc = empty($array_names) ? $associative : $array_names;
        $nameAssoc = is_int($associative[0]) ? $associative : $nameAssoc;   
        
        $temp = array();
        for($i=0;$i<$totalElements;++$i){
            for($j=0;$j<$totalAssoc;++$j){
                $k=0;
                foreach ($_array[$associative[$j]] as $key => $value) {
                    if($key==$i){  
                        $key = (is_int($associative[0]) && !empty($array_names)) ? $array_names[$k] : $key;
                        $temp[$key][$nameAssoc[$j]] = $value;
                    }
                    else{
                        continue;
                    } 
                    ++$k;
                }                            
            } 
        }  
        
        return $temp;
    }        
    
}