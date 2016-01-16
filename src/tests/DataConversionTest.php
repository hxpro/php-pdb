<?php namespace phppdb\tests;

use PHPUnit_Framework_TestCase;
use phppdb\PalmDB;

class DataConversionTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var PalmDB
     */
    protected $palmdb;

    protected function setUp() {
        $this->palmdb = new PalmDB();
    }

    public function testWriteInt8() {
        $this->assertEquals('28', PalmDB::Int8(40));
    }

    public function testReadInt8() {
        $this->assertEquals(40, $this->palmdb->LoadInt8(pack('H*', '28')));
    }

    public function testWriteInt16() {
        $this->assertEquals('0704', $this->palmdb->Int16(1796));
    }

    public function testReadInt16() {
        $this->assertEquals(1796, $this->palmdb->LoadInt16(pack('H*', '0704')));
    }

    public function testWriteInt32() {
        $this->assertEquals('02655412', $this->palmdb->Int32(40195090));
    }

    public function testReadInt32() {
        $this->assertEquals(40195090, $this->palmdb->LoadInt32(pack('H*', '02655412')));
    }

    public function testDoubleWrite() {
        $this->assertEquals('40250f5c28f5c28f', $this->palmdb->Double(10.53));
    }

    public function testDoubleRead() {
        $this->assertEquals(10.53, $this->palmdb->LoadDouble(pack('H*', '40250f5c28f5c28f')));
    }

    public function testStringWrite() {
        $this->assertEquals('616263', $this->palmdb->String('abcd', '3'));
    }

    public function testStringRead() {
        $this->assertEquals('abc', $this->palmdb->LoadString(pack('H*', '6162630000'), 4));
    }

}
