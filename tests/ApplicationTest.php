<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApplicationTest extends TestCase {

    use DatabaseTransactions;

    public function setUp() {
        parent::setUp();
    }

    /**
     * Testing static get by Name
     *
     * @return void
     */
    public function testGetByName() {

        $entity = factory(Zoe\Application::class)->create();
        $name = $entity->name;
        $app = Zoe\Application::getByName($name);
        $this->assertEquals($entity->id, $app->id);

        $name = 'Fake name';
        $app = Zoe\Application::getByName($name);
        $this->assertNull($app);

        $name = '';
        $app = Zoe\Application::getByName($name);
        $this->assertNull($app);

        $name = null;
        $app = Zoe\Application::getByName($name);
        $this->assertNull($app);
    }

    /**
     * Testing available trial stuff.
     */
    public function testGetAvailableTrials() {

        $app = factory(Zoe\Application::class)->create();
        $trial_type = factory(Zoe\TrialType::class)->create();

        $ttta = new Zoe\TrialTypesToApplication();
        $ttta->application()->associate($app);
        $ttta->trialType()->associate($trial_type);
        $ttta->save();

        $tt = $app->getAvailableTrials();
        $this->assertNotNull($tt);
        $this->assertTrue(count($tt) > 0);
        $this->assertEquals($tt[0]->id, $trial_type->id);

        $app2 = factory(Zoe\Application::class)->create();
        
        $tt = $app2->getAvailableTrials();
        $this->assertTrue(count($tt) == 0);        
    }

}
