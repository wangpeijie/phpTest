<?php

class ArrayHelper
{

        //********************************对象转化为数组**************************//
        /**
         * $u = User::findFirstById('1');
         * include_once __DIR__ . '/../../library/array.function.php';
         * $m = new \ArrayHelper();
         * print_r($m->toArray($u, ['Multiple\Backend\Models\User' => ['a' => 'id', 'b' => 'password']])); 
         * 结果：Array ( [a] => 1 [b] => $2y$10$l7bo79jFbt82QXqa2qIZpeGIWLnXZoWK.L99MsKzb5H ) 
         * 
         * print_r($m->toArray($u, ['Multiple\Backend\Models\User' => ['id', 'password']]));
         * Array ( [id] => 1 [password] => $2y$10$l7bo79jFbt82QXqa2qIZpeGIWLnXZoWK.L99MsKzb5H ) 
         * 
         * print_r($m->toArray($u));
         * Array ( [tableName] => [where] => [set] => [db] => [order] => [page] => [group] => [id] => 1 [account] => 18817337906 [password] => $2y$10$l7bo79jFbt82QXqa2qIZpeGIWLnXZoWK.L99MsKzb5H ) 
         */
        function __construct()
        {
                
        }

        public static function toArray($object, $properties = [], $recursive = true)
        {
                if (is_array($object))
                {
                        if ($recursive)
                        {
                                foreach ($object as $key => $value)
                                {
                                        if (is_array($value) || is_object($value))
                                        {
                                                $object[$key] = static::toArray($value, $properties, true);
                                        }
                                }
                        }
                        return $object;
                } elseif (is_object($object))
                {
                        if (!empty($properties))
                        {
                                $className = get_class($object);
                                if (!empty($properties[$className]))
                                {
                                        $result = [];
                                        foreach ($properties[$className] as $key => $name)
                                        {
                                                if (is_int($key))
                                                {
                                                        $result[$name] = $object->$name;
                                                } else
                                                {
                                                        $result[$key] = static::getValue($object, $name);
                                                }
                                        }
                                        return $recursive ? static::toArray($result, $properties) : $result;
                                }
                        }
                        $result = [];
                        foreach ($object as $key => $value)
                        {
                                $result[$key] = $value;
                        }
                        return $recursive ? static::toArray($result, $properties) : $result;
                } else
                {
                        return [$object];
                }
        }

        public static function getValue($array, $key, $default = null)
        {
                if ($key instanceof \Closure)
                {
                        return $key($array, $default);
                }

                if (is_array($key))
                {
                        $lastKey = array_pop($key);
                        foreach ($key as $keyPart)
                        {
                                $array = static::getValue($array, $keyPart);
                        }
                        $key = $lastKey;
                }

                if (is_array($array) && array_key_exists($key, $array))
                {
                        return $array[$key];
                }

                if (($pos = strrpos($key, '.')) !== false)
                {
                        $array = static::getValue($array, substr($key, 0, $pos), $default);
                        $key = substr($key, $pos + 1);
                }

                if (is_object($array))
                {
                        // this is expected to fail if the property does not exist, or __get() is not implemented
                        // it is not reliably possible to check whether a property is accessable beforehand
                        return $array->$key;
                } elseif (is_array($array))
                {
                        return array_key_exists($key, $array) ? $array[$key] : $default;
                } else
                {
                        return $default;
                }
        }

        /**
         * $m->merge(['a'], ['Multiple\Backend\Models\User' => ['id', 'password']])
         * Array([0] => a,[Multiple\Backend\Models\User] => Array([0] => id,[1] => password))
         */
        public static function merge($a, $b)
        {
                $args = func_get_args();
                $res = array_shift($args); //删除数组中的第一个元素，并返回被删除元素的值：
                while (!empty($args))
                {
                        $next = array_shift($args);
                        foreach ($next as $k => $v)
                        {
                                if (is_int($k))
                                {
                                        if (isset($res[$k]))
                                        {
                                                $res[] = $v;
                                        } else
                                        {
                                                $res[$k] = $v;
                                        }
                                } elseif (is_array($v) && isset($res[$k]) && is_array($res[$k]))
                                {
                                        $res[$k] = static::merge($res[$k], $v);
                                } else
                                {
                                        $res[$k] = $v;
                                }
                        }
                }
                return $res;
        }

        /**
         */
        public static function remove(&$array, $key, $default = null)
        {
                if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array)))
                {
                        $value = $array[$key];
                        unset($array[$key]);
                        return $value;
                }
                return $default;
        }

        /**
         * $array = [
         *     ['id' => '123', 'data' => 'abc', 'device' => 'laptop'],
         *     ['id' => '345', 'data' => 'def', 'device' => 'tablet'],
         *     ['id' => '345', 'data' => 'hgi', 'device' => 'smartphone'],
         * ];
         * $result = ArrayHelper::index($array, 'id');
         * ```
         *
         * The result will be an associative array, where the key is the value of `id` attribute
         * ```php
         * [
         *     '123' => ['id' => '123', 'data' => 'abc', 'device' => 'laptop'],
         *     '345' => ['id' => '345', 'data' => 'hgi', 'device' => 'smartphone']
         *     // The second element of an original array is overwritten by the last element because of the same id
         * ]
         * ```
         *
         * An anonymous function can be used in the grouping array as well.
         * ```php
         * $result = ArrayHelper::index($array, function ($element) {
         *     return $element['id'];
         * });
         *
         * $result = ArrayHelper::index($array, null, 'id');
         * [
         *     '123' => [
         *         ['id' => '123', 'data' => 'abc', 'device' => 'laptop']
         *     ],
         *     '345' => [ // all elements with this index are present in the result array
         *         ['id' => '345', 'data' => 'def', 'device' => 'tablet'],
         *         ['id' => '345', 'data' => 'hgi', 'device' => 'smartphone'],
         *     ]
         * ]
         *
         * $result = ArrayHelper::index($array, 'data', [function ($element) {
         *     return $element['id'];
         * }, 'device']);
         * ```
         *
         * The result will be a multidimensional array grouped by `id` on the first level, by the `device` on the second one
         * and indexed by the `data` on the third level:
         * ```php
         * [
         *     '123' => [
         *         'laptop' => [
         *             'abc' => ['id' => '123', 'data' => 'abc', 'device' => 'laptop']
         *         ]
         *     ],
         *     '345' => [
         *         'tablet' => [
         *             'def' => ['id' => '345', 'data' => 'def', 'device' => 'tablet']
         *         ],
         *         'smartphone' => [
         *             'hgi' => ['id' => '345', 'data' => 'hgi', 'device' => 'smartphone']
         *         ]
         *     ]
         * ]
         */
        public static function index($array, $key, $groups = [])
        {
                $result = [];
                $groups = (array) $groups;

                foreach ($array as $element)
                {
                        $lastArray = &$result;
                        foreach ($groups as $group)
                        {
                                $value = static::getValue($element, $group);
                                if (!array_key_exists($value, $lastArray))
                                {
                                        $lastArray[$value] = [];
                                }
                                $lastArray = &$lastArray[$value];
                        }

                        if ($key === null)
                        {
                                if (!empty($groups))
                                {
                                        $lastArray[] = $element;
                                }
                        } else
                        {
                                $value = static::getValue($element, $key);
                                if ($value !== null)
                                {
                                        $lastArray[$value] = $element;
                                }
                        }
                        unset($lastArray);
                }
                return $result;
        }

        /**
         * $array = [
         *     ['id' => '123', 'data' => 'abc'],
         *     ['id' => '345', 'data' => 'def'],
         * ];
         * $result = ArrayHelper::getColumn($array, 'id');
         * // the result is: ['123', '345']
         *
         * $result = ArrayHelper::getColumn($array, function ($element) {
         *     return $element['id'];
         * });
         */
        public static function getColumn($array, $name, $keepKeys = true)
        {
                $result = [];
                if ($keepKeys)
                {
                        foreach ($array as $k => $element)
                        {
                                $result[$k] = static::getValue($element, $name);
                        }
                } else
                {
                        foreach ($array as $element)
                        {
                                $result[] = static::getValue($element, $name);
                        }
                }
                return $result;
        }

        /**
         * $array = [
         *     ['id' => '123', 'name' => 'aaa', 'class' => 'x'],
         *     ['id' => '124', 'name' => 'bbb', 'class' => 'x'],
         *     ['id' => '345', 'name' => 'ccc', 'class' => 'y'],
         * ];
         *
         * $result = ArrayHelper::map($array, 'id', 'name');
         * // the result is:
         * // [
         * //     '123' => 'aaa',
         * //     '124' => 'bbb',
         * //     '345' => 'ccc',
         * // ]
         *
         * $result = ArrayHelper::map($array, 'id', 'name', 'class');
         * // the result is:
         * // [
         * //     'x' => [
         * //         '123' => 'aaa',
         * //         '124' => 'bbb',
         * //     ],
         * //     'y' => [
         * //         '345' => 'ccc',
         * //     ],
         * // ]
         */
        public static function map($array, $from, $to, $group = null)
        {
                $result = [];
                foreach ($array as $element)
                {
                        $key = static::getValue($element, $from);
                        $value = static::getValue($element, $to);
                        if ($group !== null)
                        {
                                $result[static::getValue($element, $group)][$key] = $value;
                        } else
                        {
                                $result[$key] = $value;
                        }
                }

                return $result;
        }

        /**
         * Checks if the given array contains the specified key.
         */
        public static function keyExists($key, $array)
        {
                return array_key_exists($key, $array);
        }

        /**
         * $data = [];
         * htmlspecialchars() 函数把预定义的字符转换为 HTML 实体。
         * 预定义的字符是：
         *      & （和号）成为 &
         *      " （双引号）成为 "
         *      ' （单引号）成为 '
         *      < （小于）成为 <
         *      > （大于）成为 >
         * 可用的引号类型：
         *      ENT_COMPAT - 默认。仅编码双引号。
         *      ENT_QUOTES - 编码双引号和单引号。
         *      ENT_NOQUOTES - 不编码任何引号。
         * 无效的编码：
         *      ENT_IGNORE - 忽略无效的编码，而不是让函数返回一个空的字符串。应尽量避免，因为这可能对安全性有影响。
         *      ENT_SUBSTITUTE - 把无效的编码替代成一个指定的带有 Unicode 替代字符 U+FFFD（UTF-8）或者 &#FFFD; 的字符，而不是返回一个空的字符串。
         *      ENT_DISALLOWED - 把指定文档类型中的无效代码点替代成 Unicode 替代字符 U+FFFD（UTF-8）或者 &#FFFD;。
         * 规定使用的文档类型的附加 flags：
         *      ENT_HTML401 - 默认。作为 HTML 4.01 处理代码。
         *      ENT_HTML5 - 作为 HTML 5 处理代码。
         *      ENT_XML1 - 作为 XML 1 处理代码。
         *      ENT_XHTML - 作为 XHTML 处理代码。
         */
        public static function htmlEncode($data, $valuesOnly = true, $charset = null)
        {
                if ($charset === null)
                {
                        $charset = $charset ? : 'UTF-8';
                }
                $d = [];
                foreach ($data as $key => $value)
                {
                        if (!$valuesOnly && is_string($key))
                        {
                                $key = htmlspecialchars($key, ENT_QUOTES | ENT_SUBSTITUTE, $charset); //把预定义的字符 "<" （小于）和 ">" （大于）转换为 HTML 实体：
                        }
                        if (is_string($value))
                        {
                                $d[$key] = htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, $charset);
                        } elseif (is_array($value))
                        {
                                $d[$key] = static::htmlEncode($value, $valuesOnly, $charset);
                        } else
                        {
                                $d[$key] = $value;
                        }
                }
                return $d;
        }

        /**
         * $data=[]
         * htmlspecialchars_decode
         * 会被解码的 HTML 实体是：
         *      & 解码成 & （和号）
         *      " 解码成 " （双引号）
         *      ' 解码成 ' （单引号）
         *      < 解码成 < （小于）
         *      > 解码成 > （大于）
         * 可用的引号类型：
         *      ENT_COMPAT - 默认。仅解码双引号。
         *      ENT_QUOTES - 解码双引号和单引号。
         *      ENT_NOQUOTES - 不解码任何引号。
         * 规定使用的文档类型的附加 flags：
         *      ENT_HTML401 - 默认。作为 HTML 4.01 处理代码。
         *      ENT_HTML5 - 作为 HTML 5 处理代码。
         *      ENT_XML1 - 作为 XML 1 处理代码。
         *      ENT_XHTML - 作为 XHTML 处理代码。
         */
        public static function htmlDecode($data, $valuesOnly = true)
        {
                $d = [];
                foreach ($data as $key => $value)
                {
                        if (!$valuesOnly && is_string($key))
                        {
                                $key = htmlspecialchars_decode($key, ENT_QUOTES);
                        }
                        if (is_string($value))
                        {
                                $d[$key] = htmlspecialchars_decode($value, ENT_QUOTES); //htmlspecialchars_decode() 函数把预定义的 HTML 实体转换为字符。
                        } elseif (is_array($value))
                        {
                                $d[$key] = static::htmlDecode($value);
                        } else
                        {
                                $d[$key] = $value;
                        }
                }
                return $d;
        }
///////////////////////////////////////下面基本没什么用//////////////////////////////////
        /**
         * Returns a value indicating whether the given array is an associative array.
         */
        public static function isAssociative($array, $allStrings = true)
        {
                if (!is_array($array) || empty($array))
                {
                        return false;
                }
                if ($allStrings)
                {
                        foreach ($array as $key => $value)
                        {
                                if (!is_string($key))
                                {
                                        return false;
                                }
                        }
                        return true;
                } else
                {
                        foreach ($array as $key => $value)
                        {
                                if (is_string($key))
                                {
                                        return true;
                                }
                        }
                        return false;
                }
        }

        /**
         * Returns a value indicating whether the given array is an indexed array.
         */
        public static function isIndexed($array, $consecutive = false)
        {
                if (!is_array($array))
                {
                        return false;
                }

                if (empty($array))
                {
                        return true;
                }

                if ($consecutive)
                {
                        return array_keys($array) === range(0, count($array) - 1);
                } else
                {
                        foreach ($array as $key => $value)
                        {
                                if (!is_int($key))
                                {
                                        return false;
                                }
                        }
                        return true;
                }
        }

        /**
         * 
         */
        public static function isIn($needle, $haystack, $strict = false)
        {
                if ($haystack instanceof \Traversable)
                {
                        foreach ($haystack as $value)
                        {
                                if ($needle == $value && (!$strict || $needle === $haystack))
                                {
                                        return true;
                                }
                        }
                } elseif (is_array($haystack))
                {
                        return in_array($needle, $haystack, $strict);
                } else
                {
                        throw new InvalidParamException('Argument $haystack must be an array or implement Traversable');
                }

                return false;
        }

        /**
         * 
         */
        public static function isTraversable($var)
        {
                return is_array($var) || $var instanceof \Traversable;//Traversable  检测一个类是否可以使用 foreach 进行遍历的接口。
        }

}
