<?php
namespace App;


trait EncryptsFields
{
    protected $encryptable = [];

    public function getAttribute($key)
    {
        if (in_array($key, $this->encryptable)) {
            return decrypt(parent::getAttribute($key));
        }
        return parent::getAttribute($key);;
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable)) {
            $value = encrypt($value);
        }
        return parent::setAttribute($key, $value);
    }
}