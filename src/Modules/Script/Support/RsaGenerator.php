<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 24/10/17
 * Time: 20:07
 */

namespace Modules\Script\Support;

class RsaGenerator
{
    private static $instance;

    /**
     * RsaGenerator constructor.
     */
    private function __construct()
    {
    }

    public static function generateKeyPair($keySize = 1024): RsaKeyPair
    {
        if (self::$instance === null)
            self::$instance = new RsaGenerator();

        return self::$instance->generate($keySize);
    }

    private function generate($keySize)
    {
        $rsaKey = openssl_pkey_new(array(
            'private_key_bits' => $keySize,
            'private_key_type' => OPENSSL_KEYTYPE_RSA));

        $privKey = openssl_pkey_get_private($rsaKey);
        openssl_pkey_export($privKey, $pem); //Private Key
        $pubKey = $this->sshEncodePublicKey($rsaKey); //Public Key

        $umask = umask(0066);
        return new RsaKeyPair(str_replace(PHP_EOL, '', $pubKey), str_replace(PHP_EOL, '', $pem));
    }

    private function sshEncodePublicKey($privKey)
    {
        $keyInfo = openssl_pkey_get_details($privKey);
        $buffer = pack("N", 7) . "ssh-rsa" .
            $this->sshEncodeBuffer($keyInfo['rsa']['e']) .
            $this->sshEncodeBuffer($keyInfo['rsa']['n']);
        return "ssh-rsa " . base64_encode($buffer);
    }

    private function sshEncodeBuffer($buffer)
    {
        $len = strlen($buffer);
        if (ord($buffer[0]) & 0x80) {
            $len++;
            $buffer = "\x00" . $buffer;
        }
        return pack("Na*", $len, $buffer);
    }
}
