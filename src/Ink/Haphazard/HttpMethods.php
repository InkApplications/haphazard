<?php
/**
 * File HttpMethods.php
 */

namespace Ink\Haphazard;

/**
 * Class HttpMethods
 *
 * Enum of allowable HTTP methods
 *
 * @package Ink\Haphazard
 * @author Nate Brunette <n@tebru.net>
 */
final class HttpMethods
{
    /**#@+
     * Allowable HTTP methods
     */
    const HEAD = 'HEAD';
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';
    const TRACE = 'TRACE';
    const OPTIONS = 'OPTIONS';
    const CONNECT = 'CONNECT';
    /**#@-*/

    /**
     * An array of the allowable HTTP methods
     *
     * @var array $values
     */
    private static $values = array(
        'HEAD' => self::HEAD,
        'GET' => self::GET,
        'POST' => self::POST,
        'PUT' => self::PUT,
        'DELETE' => self::DELETE,
        'TRACE' => self::TRACE,
        'OPTIONS' => self::OPTIONS,
        'CONNECT' => self::CONNECT,
    );

    /**
     * The class may not be instantiated
     */
    private function __construct() {}

    /**
     * Get the enum value from string
     *
     * This will check that the string provided is a valid value and will return
     * the enum value if it is.
     *
     * @param string $method The HTTP method
     *
     * @return string
     */
    public static function valueOf($method)
    {
        $method = self::normalize($method);
        self::assertValue($method);

        return self::$values[$method];
    }

    /**
     * Return an array of all possible enum values
     *
     * @return array
     */
    public static function values()
    {
        return self::$values;
    }

    /**
     * Throws an exception if the value does not exist in the enum
     *
     * @param string $value The HTTP method
     *
     * @throws \UnexpectedValueException
     */
    private static function assertValue($value)
    {
        if (in_array($value, self::$values)) {
            return;
        }

        throw new \UnexpectedValueException('Value is not an allowed HTTP method');
    }

    /**
     * Normalizes string values in a common format
     *
     * All HTTP method references will be upper case
     *
     * @param string $value The HTTP method
     *
     * @return string
     */
    private static function normalize($value)
    {
        return strtoupper($value);
    }
}