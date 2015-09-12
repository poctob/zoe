<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HTTPTest extends TestCase {

    use DatabaseTransactions;

    public function setUp() {
        parent::setUp();
    }

    /**
     * Home Page
     *
     * @return void
     */
    public function testHome() {
        $this->visit('/')
                ->see('South Carolina Medicaid Report Converter');

        $this->visit('/')
                ->click('Register')
                ->seePageIs('/auth/register');

        $this->visit('/')
                ->click('Forgot Your Password?')
                ->seePageIs('/password/email');
    }

    /**
     * Register Page
     *
     * @return void
     */
    public function testRegister() {
        $this->visit('/auth/register')
                ->see('Register')
                ->dontSee('Applications');

        $this->visit('/auth/register')
                ->type('Kevin Bacon', 'name')
                ->type('kevein@bacon.com', 'email')
                ->type('bacon', 'password')
                ->type('bacon', 'password_confirmation')
                ->press('Register')
                ->seePageIs('/auth/register')
                ->see('problems');

        $this->visit('/auth/register')
                ->type('Kevin Bacon', 'name')
                ->type('kevein@bacon.com', 'email')
                ->type('baconkevin', 'password')
                ->type('baconkevin', 'password_confirmation')
                ->press('Register')
                ->seePageIs('/home')
                ->dontSee('problems');
    }

    /**
     * Login Page
     *
     * @return void
     */
    public function testLogin() {
        $this->visit('/auth/login')
                ->see('Login')
                ->dontSee('Applications');
    }

    /**
     * Applications Page
     *
     * @return void
     */
    public function testApplications() {
        $this->visit('/applications')
                ->see('Login')
                ->dontSee('Applications');
    }

    public function testRegisteredUser() {
        $user = factory(Zoe\User::class)->create();
        $this->actingAs($user)
                ->visit('/')
                ->see('Start Your Free Trial')
                ->see('Sign Up For Monthly Subscription');

        $this->actingAs($user)
                ->visit('/applications')
                ->see('Start Your Free Trial')
                ->see('Sign Up For Monthly Subscription');

        $this->actingAs($user)
                ->visit('/subscriptions')
                ->see('Start Your Free Trial')
                ->see('Sign Up For Monthly Subscription');

        $this->actingAs($user)
                ->visit('/profile')
                ->see($user->name);
    }

    public function testStartTrial() {
        $user = factory(Zoe\User::class)->create();
        $this->actingAs($user)
                ->visit('/')
                ->see('Start Your Free Trial')
                ->press('Start Your Free Trial')
                ->seePageIs('/applications');
    }

    public function testTrialUser() {
        $user = factory(Zoe\User::class)->create();
        $u2 = MockUser::find($user->id);
        $u2->setSubscribed(false);
        $u2->setExpired(false);
        $u2->setTrial_allowed(true);
        $u2->setTrial_expired(false);

        $this->actingAs($u2)
                ->visit('/')
                ->see('SC Medicaid Converter')
                ->see('Upload your file below:');

        $this->actingAs($u2)
                ->visit('/applications')
                ->see('SC Medicaid Converter')
                ->see('Upload your file below:')
                ->dontSee('Start Your Free Trial')
                ->dontSee('Sign Up For Monthly Subscription');

        $this->actingAs($u2)
                ->visit('/subscriptions')
                ->dontSee('Start Your Free Trial')
                ->see('Trial')
                ->see('Sign Up For Monthly Subscription');

        $this->actingAs($u2)
                ->visit('/profile')
                ->see($user->name);
    }

    public function testTrialExpiredUser() {
        $user = factory(Zoe\User::class)->create();
        $u2 = MockUser::find($user->id);
        $u2->setSubscribed(false);
        $u2->setExpired(false);
        $u2->setTrial_allowed(true);
        $u2->setTrial_expired(true);

        $this->actingAs($u2)
                ->visit('/')
                ->dontSee('Start Your Free Trial')
                ->see('Sign Up For Monthly Subscription');

        $this->actingAs($u2)
                ->visit('/applications')
                ->dontSee('SC Medicaid Converter')
                ->dontSee('Upload your file below:')
                ->dontSee('Start Your Free Trial')
                ->see('Sign Up For Monthly Subscription');

        $this->actingAs($u2)
                ->visit('/subscriptions')
                ->dontSee('Start Your Free Trial')
                ->see('Trial')
                ->see('Sign Up For Monthly Subscription');

        $this->actingAs($u2)
                ->visit('/profile')
                ->see($user->name);
    }

    public function testSubscribedUser() {
        $user = factory(Zoe\User::class)->create();
        $u2 = MockUser::find($user->id);
        $u2->setSubscribed(true);
        $u2->setPlan(config('zoe.application')['NAME']);
        $u2->setExpired(false);
        $u2->setTrial_allowed(true);
        $u2->setTrial_expired(true);

        $this->actingAs($u2)
                ->visit('/')
                ->dontSee('Start Your Free Trial')
                ->dontsee('Sign Up For Monthly Subscription')
                ->see('SC Medicaid Converter')
                ->see('Upload your file below:');

        $this->actingAs($u2)
                ->visit('/applications')
                ->dontSee('Start Your Free Trial')
                ->dontsee('Sign Up For Monthly Subscription')
                ->see('SC Medicaid Converter')
                ->see('Upload your file below:');

        $this->actingAs($u2)
                ->visit('/subscriptions')
                ->dontSee('Start Your Free Trial')
                ->dontSee('Trial')
                ->dontSee('Sign Up For Monthly Subscription')
                ->see('Active')
                ->see('Standard');

        $this->actingAs($u2)
                ->visit('/profile')
                ->see($user->name);
    }
    
    public function testFullyExpiredUser() {
        $user = factory(Zoe\User::class)->create();
        $u2 = MockUser::find($user->id);
        $u2->setSubscribed(true);
        $u2->setPlan(config('zoe.application')['NAME']);
        $u2->setExpired(true);
        $u2->setTrial_allowed(true);
        $u2->setTrial_expired(true);

        $this->actingAs($u2)
                ->visit('/')
                ->dontSee('Start Your Free Trial')
                ->see('Sign Up For Monthly Subscription')
                ->dontSee('SC Medicaid Converter')
                ->dontSee('Upload your file below:');

        $this->actingAs($u2)
                ->visit('/applications')
                ->dontSee('Start Your Free Trial')
                ->see('Sign Up For Monthly Subscription')
                ->dontSee('SC Medicaid Converter')
                ->dontSee('Upload your file below:');

        $this->actingAs($u2)
                ->visit('/subscriptions')
                ->dontSee('Start Your Free Trial')
                ->see('Sign Up For Monthly Subscription');

        $this->actingAs($u2)
                ->visit('/profile')
                ->see($user->name);
    }

}
