<?php

class ColumnTest extends TestCase {

    private $entity;

    public function setUp() {
        parent::setUp();
        $this->entity = new Zoe\Lib\PDF2DF\Column;
    }

    /**
     * Testing width getter and setter
     *
     * @return void
     */
    public function testGetWidth() {
        $input = 100;
        $this->entity->setWidth($input);
        $output = $this->entity->getWidth();
        $this->assertEquals($input, $output);

        $input2 = 'Foo';
        $this->entity->setWidth($input2);
        $output = $this->entity->getWidth();
        $this->assertNotEquals($input2, $output);
        $this->assertEquals($input, $output);
        
        $input3 = '-100';
        $this->entity->setWidth($input3);
        $output = $this->entity->getWidth();
        $this->assertNotEquals($input3, $output);
        $this->assertEquals($input, $output);
        
        $input4 = '0';
        $this->entity->setWidth($input4);
        $output = $this->entity->getWidth();
        $this->assertNotEquals($input4, $output);
        $this->assertEquals($input, $output);
    }

    /**
     * Testing width getter and setter
     *
     * @return void
     */
    public function testGetHeader() {
        $input = 'Test header';
        $this->entity->setHeader($input);
        $output = $this->entity->getHeader();
        $this->assertEquals($input, $output);
        
        $input2 = 5;
        $this->entity->setHeader($input2);
        $output = $this->entity->getHeader();
        $this->assertNotEquals($input2, $output);
        $this->assertEquals($input, $output);
        
        $input3 = null;
        $this->entity->setHeader($input3);
        $output = $this->entity->getHeader();
        $this->assertNotEquals($input3, $output);
        $this->assertEquals($input, $output);
    }

}
