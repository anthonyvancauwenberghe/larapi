<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 26/10/17
 * Time: 14:43
 */

namespace Modules\Script\Support;


class RsaKeyPair
{
    private $public_key;
    private $private_key;

    /**
     * Rsa constructor.
     * @param $public_key
     * @param $private_key
     */
    public function __construct($public_key, $private_key)
    {
        $this->public_key = $public_key;
        $this->private_key = $private_key;
    }

    /**
     * @return mixed
     */
    public function getPublicKey()
    {
        return $this->public_key;
    }

    /**
     * @return mixed
     */
    public function getPrivateKey()
    {
        return $this->private_key;
    }

    public function toArray()
    {
        return [
            "public_key" => $this->getPublicKey(),
            "private_key" => $this->getPrivateKey()
        ];
    }


}
