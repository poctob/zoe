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
     * Test the constructor
     * @return [type] [description]
     */
    public function testConstructor() {
        $this->assertInstanceOf('\Zoe\Lib\PDF2DF\ExcelWriter', $this->entity);
    }

    /**
     * Testing width getter and setter
     *
     * @return void
     */
    public function testAddCurrency() {

        $this->entity = new \Zoe\Lib\PDF2DF\ExcelWriter($this->file);
        $input = NULL;
        $this->entity->addCurrency($input, 1, 0);
        $this->entity->writeToDisk();

        $actual = $this->getOutput();

        $this->assertNull($actual);

        $this->entity = new \Zoe\Lib\PDF2DF\ExcelWriter($this->file);
        $input = -1;
        $this->entity->addCurrency($input, 1, 0);
        $this->entity->writeToDisk();

        $actual = $this->getOutput();

        $this->assertNull($actual);

        $this->entity = new \Zoe\Lib\PDF2DF\ExcelWriter($this->file);
        $input = 1250.23;
        $this->entity->addCurrency($input, 1, 0);
        $this->entity->writeToDisk();

        $actual = $this->getOutput();

        $this->assertEquals($input, $actual);
    }

    /**
     * Testing adding date
     *
     * @return void
     */
    public function testAddDate() {

        $this->entity = new \Zoe\Lib\PDF2DF\ExcelWriter($this->file);
        $input = NULL;
        $this->entity->addDate($input, 1, 0);
        $this->entity->writeToDisk();

        $actual = $this->getOutput();

        $this->assertNull($actual);

        $this->entity = new \Zoe\Lib\PDF2DF\ExcelWriter($this->file);
        $input = -1;
        $this->entity->addDate($input, 1, 0);
        $this->entity->writeToDisk();

        $actual = $this->getOutput();

        $this->assertNull($actual);

        $this->entity = new \Zoe\Lib\PDF2DF\ExcelWriter($this->file);
        $input = mktime(0, 0, 0, 11, 26, 2015);
        $this->entity->addDate($input, 1, 0);
        $this->entity->writeToDisk();

        $actual = $this->getOutput()->timestamp;

        $this->assertEquals($input, $actual);
    }

    /**
     * Testing adding string
     *
     * @return void
     */
    public function testAddString() {

        $this->entity = new \Zoe\Lib\PDF2DF\ExcelWriter($this->file);
        $input = NULL;
        $this->entity->addString($input, 1, 0);
        $this->entity->writeToDisk();

        $actual = $this->getOutput();

        $this->assertNull($actual);

        $this->entity = new \Zoe\Lib\PDF2DF\ExcelWriter($this->file);
        $input = '';
        $this->entity->addString($input, 1, 0);
        $this->entity->writeToDisk();

        $actual = $this->getOutput();

        $this->assertNull($actual);

        $this->entity = new \Zoe\Lib\PDF2DF\ExcelWriter($this->file);
        $input = 'Random String Testing Unit Php';
        $this->entity->addString($input, 1, 0);
        $this->entity->writeToDisk();

        $actual = $this->getOutput();

        $this->assertEquals($input, $actual);
    }
    
     /**
     * Testing adding header
     *
     * @return void
     */
    public function testAddHeader() {
          
        $this->entity = new \Zoe\Lib\PDF2DF\ExcelWriter($this->file);
        $input = NULL;
        $this->entity->addHeader($input, 1, 0);
        $this->entity->writeToDisk();
        
        $actual = $this->getOutput();

        $this->assertNull($actual);
        
        $this->entity = new \Zoe\Lib\PDF2DF\ExcelWriter($this->file);
        $input = '';
        $this->entity->addHeader($input, 1, 0);
        $this->entity->writeToDisk();
        
        $actual = $this->getOutput();

        $this->assertNull($actual);
        
        $this->entity = new \Zoe\Lib\PDF2DF\ExcelWriter($this->file);
        $input = 'Random Header Testing Unit Php';
        $this->entity->addHeader($input, 1, 0);
        $this->entity->writeToDisk();
        
        $actual = $this->getOutput();

        $this->assertEquals($input, $actual);
    }

    private function getOutput() {
        $reader = Excel::load('storage/exports/' . $this->file . '.xls');
        $reader->noHeading();
        $items = $reader->toArray();
        if (count($items) > 0 && count($items[0]) > 0) {
            return $items[0][0];
        } else {
            return NULL;
        }
    }

}
