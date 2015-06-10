<?php

class ExcelWriterTest extends TestCase {

    private $entity;
    private $file;

    public function setUp() {
        parent::setUp();
        $this->file = "unittestout";
        $this->entity = new \Zoe\Lib\PDF2DF\ExcelWriter($this->file);
    }

    /**
     * Testing width getter and setter
     *
     * @return void
     */
    public function testAddCurrency() {
        $input = 1250.23;
        $this->entity->addCurrency($input, 1, 0);
        $this->entity->writeToDisk();
        
        $reader = Excel::load('storage/exports/'.$this->file.'.xls'); 
        $reader->noHeading();
        $items = $reader->toArray();        
        $actual = $items[0][0];
   
        $this->assertEquals($input, $actual);     
    }

}
