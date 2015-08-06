<?php

class ParserTest extends TestCase {

    private $entity;
    private $input;

    public function setUp() {
        parent::setUp();
        $this->input = "tests/files/unittestin.pdf";
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
        $this->entity->convert();
        $this->assertTrue(file_exists('storage/exports/unittestout.xls'));
    }

    public function testIsValid() {
        $this->assertTrue($this->entity->isFileValid());

        $entity = new \Zoe\Lib\PDF2DF\Parser("tests/files/dummy.pdf",
                'unittestout', new Zoe\Lib\PDF2DF\Alert(),
                new \Zoe\Lib\PDF2DF\Progress());

        $this->assertFalse($entity->isFileValid());

        $entity = new \Zoe\Lib\PDF2DF\Parser("tests/files/onepage.pdf",
                'unittestout', new Zoe\Lib\PDF2DF\Alert(),
                new \Zoe\Lib\PDF2DF\Progress());

        $this->assertFalse($entity->isFileValid());

        $entity = new \Zoe\Lib\PDF2DF\Parser("tests/files/dummytest.txt",
                'unittestout', new Zoe\Lib\PDF2DF\Alert(),
                new \Zoe\Lib\PDF2DF\Progress());

        $this->assertFalse($entity->isFileValid());
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
