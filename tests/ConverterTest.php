<?php

class ConverterTest extends TestCase {

    private $entity;

    public function setUp() {
        parent::setUp();
        $this->entity = new \Zoe\Lib\util\Converter;
    }

    /**
     * Testing width getter and setter
     *
     * @return void
     */
    public function testConvertCurrency() {
        $input = '1250.23';
        $expected = 1250.23;
        $actual = \Zoe\Lib\util\Converter::convertCurrency($input);
        $this->assertEquals($expected, $actual);     
        
        $input = 'junk';
        $expected = 0;
        $actual = \Zoe\Lib\util\Converter::convertCurrency($input);
        $this->assertEquals($expected, $actual); 
    }

    /**
     * Testing width getter and setter
     *
     * @return void
     */
    public function testConvertDate() {
        $input = '010115';
        $expected = '2015-01-01 00:00:00';
        $actual = \Zoe\Lib\util\Converter::convertDate($input);
        $this->assertEquals($expected, $actual);
        
        $input = 'junk';
        $expected = '0';
        $actual = \Zoe\Lib\util\Converter::convertDate($input);
        $this->assertEquals($expected, $actual);
    }

}
