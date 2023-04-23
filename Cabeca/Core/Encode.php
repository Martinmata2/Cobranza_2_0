<?php
namespace Cabeca\Core;


/**
 * Codificar y decodificar strings
 * @author Marti
 *
 */
class Encode
{

    public static function sha1md5encode($value)
    {
        return md5(sha1($value));
    }
    // Updated code from comments
    public static function encode($value)
    {
        if (! $value)
        {
            return false;
        }

        $key = sha1('EnCRypT10nK#Y!RiSRNn');
        $strLen = strlen($value);
        $keyLen = strlen($key);
        $j = 0;
        $crypttext = '';

        for ($i = 0; $i < $strLen; $i ++)
        {
            $ordStr = ord(substr($value, $i, 1));
            if ($j == $keyLen)
            {
                $j = 0;
            }
            $ordKey = ord(substr($key, $j, 1));
            $j ++;
            $crypttext .= strrev(base_convert(dechex($ordStr + $ordKey), 16, 36));
        }

        return $crypttext;
    }

    public static function decode($value)
    {
        if (! $value)
        {
            return false;
        }

        $key = sha1('EnCRypT10nK#Y!RiSRNn');
        $strLen = strlen($value);
        $keyLen = strlen($key);
        $j = 0;
        $decrypttext = '';

        for ($i = 0; $i < $strLen; $i += 2)
        {
            $ordStr = hexdec(base_convert(strrev(substr($value, $i, 2)), 36, 16));
            if ($j == $keyLen)
            {
                $j = 0;
            }
            $ordKey = ord(substr($key, $j, 1));
            $j ++;
            $decrypttext .= chr($ordStr - $ordKey);
        }

        return $decrypttext;
    }
    
    public static function hashPassword($password)
    {
        $options = [
            'cost' => 11
        ];
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }
    
    public static function verifyPassword($hash, $password)
    {
        return password_verify($password, $hash);
    }
}

