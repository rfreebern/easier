<?php

namespace rfreebern;

class Easier {

    private static $Digits = array(
        '0','1','2','3','4','5','6','7','8','9',
        'a','c','d','e','f','h','j','k','m','n',
        'p','r','t','v','w','x','y'
    );

    private static $Disambiguate = array(
        'o' => '0', 'q' => '0',
        'i' => '1', 'l' => '1',
        'z' => '2',
        's' => '5',
        'g' => '6',
        'b' => '8',
        'u' => 'v'
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
        return preg_match($exp, strtolower($input)) === 1;
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

        $input = str_replace($disambiguateKeys, $disambiguateVals, strtolower($input));
        
        $decoded = 0;
        for ($i = 0; $i < strlen($input); $i++) {
            $decoded = bcadd($decoded, bcmul(strpos($digitString, $input[$i]), bcpow(27, $i)));
        }
        
        return $decoded;
    }

}
