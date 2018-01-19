<?php

namespace Tests;
use Laravel\BrowserKitTesting\TestCase as BaseTestCase;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;


abstract class TestCaseWithPermission extends BaseTestCase
{
    use CreatesApplication;

    public $user;
    public $emailDRIVER;

    public function setUp(){
        parent::setUp();
        $user = factory(\App\Models\User::class)->make(['id' => 10000]);
        $user->save();
        $user->givePermissionTo(Permission::all());
        $this->emailDRIVER = config('mail.driver');
        config(['mail.driver' => 'log']);
        $this->user = $user;
    }

    public function tearDown()
    {
        DB::table('model_has_permissions')->where('model_id', $this->user->id)->delete();
        $this->user->delete();
        config(['mail.driver' => $this->emailDRIVER]);
        parent::tearDown();
    }
    
    public $baseUrl = 'http://localhost';
}

