<?php


namespace Perfico\CoreBundle\Util;

/**
 * Class ReflectionHelper
 * @package Perfico\CoreBundle\Util
 */
class ReflectionHelper
{

    /**
     * Return filtered constants of class
     *
     * @param $class
     * @param $regex
     * @return array
     */
    static public function getConstants($class, $regex, $labelPrefix = '')
    {
        $r = new \ReflectionClass($class);

        $filteredKeys = array_filter(array_keys($r->getConstants()), function($const) use ($regex) {
            return preg_match($regex, $const);
        });

        $result = array();
        foreach ($filteredKeys as $filteredKey) {
            $result[$r->getConstant($filteredKey)] = $labelPrefix.$r->getConstant($filteredKey);
        }

        return $result;
    }
}