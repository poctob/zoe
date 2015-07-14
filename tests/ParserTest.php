<?php

class ParserTest extends TestCase {

    private $entity;
    private $input;

    public function setUp() {
        parent::setUp();
        $this->input = "unittestin.pdf";
        $this->entity = new \Zoe\Lib\PDF2DF\Parser($this->input, 'unittestout',
                new Zoe\Lib\PDF2DF\Alert(), new \Zoe\Lib\PDF2DF\Progress());
    }

    /**
     * Test the constructor
     * @return [type] [description]
     */
    public function testConstructor() {
        $this->assertInstanceOf('\Zoe\Lib\PDF2DF\Parser', $this->entity);
        $this->assertTrue($this->entity->isReady());
    }

    public function testConvert() {
        $seeder = new DatabaseSeeder();
        $seeder->run();
        
        $this->entity->convert();
        $this->assertTrue(file_exists('storage/exports/unittestout.xls'));
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName,
            array $parameters = array()) {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

}
