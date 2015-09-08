<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TrialTypeTest extends TestCase {

    use DatabaseTransactions;

    private $entity;

    public function setUp() {
        parent::setUp();
        $this->entity = factory(Zoe\TrialType::class)->create();
    }

    /**
     * Testing static get by Name
     *
     * @return void
     */
    public function testGetByName() {
        $name = $this->entity->name;
        $app = Zoe\TrialType::getByName($name);
        $this->assertEquals($this->entity->id, $app->id);

        $name = 'Fake name';
        $app = Zoe\TrialType::getByName($name);
        $this->assertNull($app);
        
        $name = '';
        $app = Zoe\TrialType::getByName($name);
        $this->assertNull($app);
        
        $name = null;
        $app = Zoe\TrialType::getByName($name);
        $this->assertNull($app);
    }

}
