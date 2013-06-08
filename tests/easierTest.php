<?php

namespace Easier;

include_once('lib/easier.php');

class EasierTest extends \PHPUnit_Framework_TestCase {

    public function testStaticEncode () {

        $this->assertEquals(Easier::encode(0), '');
        $this->assertEquals(Easier::encode('12'), 'd');
        $this->assertEquals(Easier::encode(123456789), 'mv68j8');

    }

    public function testStaticDecode () {

        $this->assertEquals(Easier::decode(''), '0');
        $this->assertEquals(Easier::decode('d'), '12');
        $this->assertEquals(Easier::decode('mv68j8'), 123456789);
        $this->assertEquals(Easier::decode('mvG8j8'), 123456789);
        $this->assertEquals(Easier::decode('mv6bj8'), 123456789);
        $this->assertEquals(Easier::decode('mv68jB'), 123456789);
        $this->assertEquals(Easier::decode('mUgbjB'), 123456789);

    }

    public function testValidate () {

        try {
            Easier::decode('abc-+!');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals($e->getMessage(), 'Input must be a valid easier code.');
        }

        try {
            Easier::encode('foo bar');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals($e->getMessage(), 'Input must be a decimal number.');
        }

    }

    public function testInstantiate () {

        $easier = new Easier();
        $this->assertInstanceOf('Easier\Easier', $easier);

    }

    public function testObjectEncode () {

        $easier = new Easier(123456789);
        $this->assertEquals($easier->encode(), 'mv68j8');
        $this->assertEquals($easier->encode('8675309'), 'p7p8j');

    }

    public function testObjectDecode () {

        $easier = new Easier('mv68j8');
        $this->assertEquals($easier->decode(), 123456789);
        $this->assertEquals($easier->decode('P7PBJ'), 8675309);

    }

    public function testDisambiguate () {

        $this->assertEquals(Easier::decode('555'), Easier::decode('sss'));
        $this->assertEquals(Easier::decode('8B8'), Easier::decode('B8B'));
        $this->assertEquals(Easier::decode('1LI'), Easier::decode('l1i'));
        $this->assertEquals(Easier::decode('6OQ'), Easier::decode('G0o'));
        $this->assertEquals(Easier::decode('2UV'), Easier::decode('ZVU'));

    }

    public function testIdentity () {

        $this->assertEquals(Easier::decode(Easier::encode(69105)), 69105);

    }

}
