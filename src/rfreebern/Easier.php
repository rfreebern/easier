<?php

namespace rfreebern;

class Easier {

    private static $Digits = array(
        '0','1','2','3','4','5','6','7','8','9',
        'A','C','D','E','F','H','J','K','M','N',
        'P','R','T','V','W','X','Y'
    );

    private static $Disambiguate = array(
        'O' => '0', 'Q' => '0',
        'I' => '1', 'L' => '1',
        'Z' => '2',
        'S' => '5',
        'G' => '6',
        'B' => '8',
        'U' => 'V'
    );

    protected $Input;

    public function __construct ($input = null) {
        $this->Input = $input;
        return $this;
    }

    public function __call ($method, $arguments) {
        if (is_callable(array($this, $method))) {
            if (count($arguments)) {
                return self::$method($arguments[0]);
            } else {
                return self::$method($this->Input);
            }
        }
    }

    public static function __callStatic ($method, $arguments) {
        return self::$method($arguments[0]);
    }

    private static function valid ($input) {
        $digitString = implode('', self::$Digits);
        $disambiguateKeyString = implode('', array_keys(self::$Disambiguate));
        $exp = '/^[' . $digitString . $disambiguateKeyString . ']*$/';
        return preg_match($exp, strtoupper($input)) === 1;
    }

    private static function encode ($input) {
        if (!is_numeric($input)) {
            throw new \InvalidArgumentException('Input must be a decimal number.');
        }

        $encoded = '';
        while ($input) {
            $v = bcmod($input, 27);
            $encoded .= self::$Digits[$v];
            $input = bcdiv(bcsub($input, $v), 27);
        }
        return $encoded;
    }

    private static function decode ($input) {
        if (!self::valid($input)) {
            throw new \InvalidArgumentException('Input must be a valid easier code.');
        }

        $digitString = implode('', self::$Digits);
        
        $disambiguateKeys = array_keys(self::$Disambiguate);
        $disambiguateVals = array_values(self::$Disambiguate);

        $input = str_replace($disambiguateKeys, $disambiguateVals, strtoupper($input));
        
        $decoded = 0;
        for ($i = 0; $i < strlen($input); $i++) {
            $decoded = bcadd($decoded, bcmul(strpos($digitString, $input[$i]), bcpow(27, $i)));
        }
        
        return $decoded;
    }

}
