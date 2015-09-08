<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase {

    use DatabaseTransactions;

    public function setUp() {
        parent::setUp();
    }

    /**
     * Testing static get by Name
     *
     * @return void
     */
    public function testGetTrial() {

        $user = factory(Zoe\User::class)->create();
        $app = factory(Zoe\Application::class)->create();
        $tt = factory(Zoe\TrialType::class)->create();
        
        $trial = new Zoe\Trial();
        $trial->user()->associate($user);
        $trial->application()->associate($app);
        $trial->trialType()->associate($tt);
        $trial->save();
        
        $u = Zoe\User::find($user->id);
        $output = $u->getTrial($app);
        
        $this->assertNotNull($output);
        $this->assertEquals($trial->id, $output->id);
    }

   

}
