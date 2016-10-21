<?php

namespace Zenich;

class Nonce {

    /**
     * integer minNonceLength is a minimum length on generated nonce
     */
    protected $minNonceLength = 5;

    /**
     * integer maxNonceLength is a maximum length on generated nonce
     */
    protected $maxNonceLength = 20;

    /**
     * integer nonceLifetime is a lifetime of nonce in seconds bu default one hour
     */
    protected $nonceLifetime = 3600;

    /**
     * integer minSaltLength is a minimum salt string length
     */
    protected $minSaltLength = 10;

    /**
     * string salt is a secret salt string for nonce generation
     */
    protected $salt = '';

    /**
     * string nonceParamName name of created url parameter
     */
    protected $nonceParamName = '_nonce';


    /**
     * Constructor of the class that get secret string to complicate creating nonce
     *
     * @param null|string $salt secret string or salt for nonce generation
     * @param int $length length of generated nonce
     * @param null|int $lifetime lifetime in seconds for generated nonce
     * @param null|string $nonceParamName name for URL parameter for nonce
     * @throws \Exception
     */
    public function __construct($salt = NULL, $length = 10, $lifetime = NULL, $nonceParamName = NULL){
        if( $salt === NULL || (strlen($salt) < $this->minSaltLength)) {
            throw new \Exception('Salt is empty or to short.');
        } else {
            $this->salt = $salt;
        }

        if(intval($length) >= $this->minNonceLength && intval($length) <= $this->maxNonceLength) {
            $this->nonceLength = intval($length);
        }

        if($lifetime != NULL && intval($lifetime) >= 60){
            $this->nonceLifetime = intval($lifetime);
        }

        if($nonceParamName != NULL){
            $this->nonceParamName = $nonceParamName;
        }
    }

    /**
     * Function for counting number of half periods passed from the start of The Unix Epoch
     *
     * @return int
     */
    private function calculateTick () {
        $tick = intval(ceil(time() / ( $this->nonceLifetime / 2 )));
        return $tick;
    }

    /**
     * Generate SHA1 hash from string
     *
     * @param string $string
     * @return string
     */
    private function generateHash ($string) {
        return sha1($string);
    }

    /**
     * Bit per bit comparison of the expected and the received nonce string
     *
     * @param $a
     * @param $b
     * @return bool
     */
    private function isHashEquals($a, $b) {
        $a_length = strlen($a);
        if ($a_length !== strlen($b)) {
            return false;
        }

        $result = 0;

        for ($i=0; $i<$a_length; $i++) {
            $result |= ord($a[$i]) ^ ord($b[$i]);
        }

        return $result === 0;
    }

    /**
     * Create new nonce from $string
     *
     * @param string $string to be hashed
     * @param null|integer $currentTick number of half periods passed from the start of The Unix Epoch
     * @return string is a hashed $string with considering $currentTick
     */
    public function createNonce ($string, $currentTick = NULL) {
        if($currentTick === NULL && (intval($currentTick) < ($this->calculateTick() - 2))) {
            $currentTick = $this->calculateTick();
        }

        $res = substr($this->generateHash($currentTick . $string . $this->salt), -($this->nonceLength + 2), $this->nonceLength);

        return $res;
    }

    /**
     * Comparison of the received and the expected nonce
     *
     * @param null|string $nonce
     * @param null|string $string
     * @return bool
     */
    public function checkNonce ($nonce = NULL, $string = NULL) {
        if ($nonce === NULL || $string === NULL) {
            echo 'False';
            return false;
        }

        $currentTick = $this->calculateTick();

        // Nonce generated 0 - 1/2 lifetime ago
        $expected = $this->createNonce($string, $currentTick);

        if ($this->isHashEquals($expected, $nonce)) {
            return true;
        }

        // Nonce generated 1/2 - 1 lifetime ago
        $expected = $this->createNonce($string, $currentTick - 1);

        if ($this->isHashEquals($expected, $nonce)) {
            return true;
        }

        return false;
    }

    /**
     * Function for returning nonce as url part
     *
     * @param string $string url or part of url that should be hashed
     * @param null|string $nonceParamName name of custom parameter instead of standard
     * @return string as a part of url
     */
    public function nonceUrl ($string, $nonceParamName = NULL) {
        if ($nonceParamName != NULL) {
            $this->nonceParamName = strval($nonceParamName);
        }
        return urlencode($this->nonceParamName . "=" . $this->createNonce($string));
    }
}