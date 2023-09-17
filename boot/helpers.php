<?php

use Boot\Container;

if (!function_exists('container')) {
    /**
     * Access the available container instance.
     *
     * @param ?string $abstract
     * @param array $parameters
     * @return mixed
     */
    function container(string $abstract = null, array $parameters = []): mixed
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($abstract, $parameters);
    }
}

if (!function_exists('array_first')) {
    /**
     * Get the first element in array
     *
     * @param array $array
     * @return mixed
     */
    function array_first(array $array): mixed
    {
        if (($firstElement = reset($array)) === false) {
            return '';
        }
        return $firstElement;
    }
}

if (!function_exists('array_last')) {
    /**
     * Get the last element in array
     * @param array $array
     * @return mixed
     */
    function array_last(array $array): mixed
    {
        if (($lastElement = end($array)) === false) {
            return '';
        }
        return $lastElement;
    }
}

if (!function_exists('camel_case_to_snake_case')) {
    /**
     * Convert string camel case style to snake case
     *
     * @param string $input
     * @return string
     */
    function camel_case_to_snake_case(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
}

if (!function_exists('snake_case_to_camel_case')) {
    /**
     * Convert string snake case style to camel case
     *
     * @param string $input
     * @return string
     */
    function snake_case_to_camel_case(string $input): string
    {
        return lcfirst(str_replace('_', '', ucwords($input, '_')));
    }
}