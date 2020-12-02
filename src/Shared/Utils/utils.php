<?php

declare(strict_types=1);

namespace App\Shared\Utils;

use Ramsey\Uuid\Uuid;

/**
 * Generates a random string
 * @param int $length
 * @return string
 */
function generateRandom(int $length = 8): string
{
    $alphabet = 'QWERTYUIOPASDFGHJKLZXCVBNM1234567890';
    $maxNumber = strlen($alphabet);
    $token = '';

    for ($i = 0; $i < $length; $i++) {
        $pos = random_int(0, $maxNumber - 1);
        $token .= $alphabet[$pos];
    }

    return $token;
}

/**
 * Generates a unique token
 * @return string
 */
function generateUniqueToken(): string
{
    return md5(Uuid::uuid4()->toString());
}

/**
 * Masks a given value
 * @param string $value
 * @param int $left Values visible on the left side
 * @param int $right Values visible on the right side
 * @param string $char Char used to mask
 * @return string
 */
function mask(string $value, int $left = 0, int $right = 0, $char = '*'): string
{
    $result = '';
    $valueLength = strlen($value);
    $maskedLength = $valueLength - ($right + $left);

    if (($left + $right + abs($maskedLength)) > $valueLength) {
        throw new \InvalidArgumentException('Total of left and right lengths don\'t match value length.');
    }

    $result .= substr($value, 0, $left);
    $result .= str_repeat($char, $maskedLength);
    if ($right > 0) {
        $result .= substr($value, ($right * -1));
    }

    return $result;
}

/**
 * Get user IP
 * @return string
 */
function getUserIP(): string
{
    // if application is executed by command
    if (php_sapi_name() === 'cli') {
        return '127.0.0.1';
    }

    $client = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote = $_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }

    return $ip;
}

/**
 * Adds item to array path
 * @param array $arr
 * @param string $path
 * @param mixed $value
 * @param string $separator
 */
function array_set_path_value(array &$arr, string $path, $value, string $separator = '.'): void
{
    $keys = explode($separator, $path);

    foreach ($keys as $key) {
        $arr = &$arr[$key];
    }

    $arr = $value;
}
