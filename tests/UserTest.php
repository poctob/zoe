<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Carbon\Carbon;

class UserTest extends TestCase {

    use DatabaseTransactions;

    public function setUp() {
        parent::setUp();
    }

    public function testTrial() {

        $user = factory(Zoe\User::class)->create();
        $app = factory(Zoe\Application::class)->create();
        $tt = factory(Zoe\TrialType::class)->create();

        $trial = new Zoe\Trial();
        $trial->user()->associate($user);
        $trial->application()->associate($app);
        $trial->trialType()->associate($tt);
        $trial->save();

        $u = Zoe\User::find($user->id);
        $output = $u->trial;

        $this->assertNotNull($output);
        $this->assertEquals($trial->id, $output->id);
    }

    public function testCanAccessApp() {
        $user = factory(Zoe\User::class)->create();
        $app = factory(Zoe\Application::class)->create();

        $tt = factory(Zoe\TrialType::class)->create();

        $trial = Zoe\Trial::makeTrial($user, $app, $tt);
        $trial->user()->associate($user);
        $trial->application()->associate($app);
        $trial->trialType()->associate($tt);
        $trial->save();

        $u = Zoe\User::find($user->id);
        $this->assertTrue($u->canAccessApp($app->name));

        $app2 = factory(Zoe\Application::class)->create();
        $app2->name = 'SC MC Test';
        $app2->save();
        $this->assertFalse($u->canAccessApp($app2->name));

        $u2 = MockUser::find($user->id);
        $u2->setSubscribed(true);
        $u2->setPlan('SC MC Test');
        $u2->setExpired(false);

        $this->assertTrue($u2->canAccessApp($app2->name));
        $this->assertFalse($u->canAccessApp($app2->name));
    }

    public function testTrialExpired() {
        $user = factory(Zoe\User::class)->create();
        $app = factory(Zoe\Application::class)->create();

        $tt = factory(Zoe\TrialType::class)->create();

        $this->assertTrue($user->trialExpired($app->name));
        
        $trial = Zoe\Trial::makeTrial($user, $app, $tt);
        $trial->user()->associate($user);
        $trial->application()->associate($app);
        $trial->trialType()->associate($tt);
        $trial->save();

        $u = Zoe\User::find($user->id);
        
        $this->assertFalse($u->trialExpired($app->name));
        
        $trial->expires = Carbon::now()->subDay();
        
        $trial->save();

        $u = Zoe\User::find($user->id);
        $this->assertTrue($u->trialExpired($app->name));
        $this->assertTrue($u->trialExpired('junk name'));
    }

    public function testGetAppTrial() {
        $user = factory(Zoe\User::class)->create();
        $app = factory(Zoe\Application::class)->create();

        $tt = factory(Zoe\TrialType::class)->create();
        
        $trial = Zoe\Trial::makeTrial($user, $app, $tt);
        $trial->save();

        $u = Zoe\User::find($user->id);
        
        $t = $u->getAppTrial($app->name);
        
        $this->assertTrue($t['trial']);
        $this->assertTrue($t['active']);
        $this->assertEquals($trial->expires, $t['expires']);
        $this->assertEquals($trial->created_at, $t['created']);
        $this->assertEquals($trial->application->name, $t['name']);
        $this->assertFalse($t['cancelled']);
        
        $app2 = factory(Zoe\Application::class)->create();
        $this->assertNull($u->getAppTrial($app2->name));                
    }
    
      public function testGetAppSubscription() {
        $user = factory(Zoe\User::class)->create();
        $app = factory(Zoe\Application::class)->create();

        $u2 = MockUser::find($user->id);
        $u2->setSubscribed(true);
        $u2->setPlan($app->name);
        $u2->setExpired(false);

        $s = $u2->getAppSubscription($app->name);
        
        $this->assertFalse($s['trial']);
        $this->assertTrue($s['active']);
        $this->assertEquals($app->name, $s['name']);
        $this->assertFalse($s['cancelled']);
        
        $u2->setSubscribed(false);
        $s = $u2->getAppSubscription($app->name);
        $this->assertNull($s);
        
        $u2->setSubscribed(true);
        $s = $u2->getAppSubscription('Junk');
        $this->assertNull($s);
      }
    
    public function testGetFillableFields() {
        $user = factory(Zoe\User::class)->create();
        $fields = $user->getFillableFields();
        
        $this->assertFalse(in_array('password', $fields));
    }
}
