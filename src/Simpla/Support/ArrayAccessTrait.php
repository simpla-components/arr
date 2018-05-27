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
 * Description of ArrayAccessTrait
 * @package 
 * @subpackage 
 * @author robert <robert.di.jesus@gmail.com>
 * @version 1.0.0
 */
trait ArrayAccessTrait
{ 
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param string $key
     * 
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->arrayExists($this->data, $key);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function offsetGet($key, $getKey = false)
    {
        return $this->getArray($this->data, $key, $getKey);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function offsetSet($key, $value, $replace = null)
    {
        $this->setArray($this->data, $key, $value, $replace);
    }

    /**
     * @param string $key
     */
    public function offsetUnset($key)
    {
        $this->unsetArray($this->data, $key);
    }
}
