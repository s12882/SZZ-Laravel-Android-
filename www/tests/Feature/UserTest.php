<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Tests\TestCaseWithPermission;
use Spatie\Permission\Models\Permission;

class UserTest extends TestCaseWithPermission
{
    use WithoutMiddleware;

    public function testCreate()
    {

        $this
            ->actingAs($this->user)
            ->visit('/')
            ->click('Pracownicy')
            ->seePageIs('/user')
            ->click('Dodaj')
            ->seePageIs('/user/create')
            ->type('Alex123', 'login')
            ->type('Alex', 'first_name')
            ->type('Nowak', 'surname')
            ->type('Nowak@gmail.com', 'email')
            ->type('123321123', 'phoneNumber')
            ->select('1', 'section_id')
            ->select('1', 'role_id')
            ->press('Zatwierdź')
            ->seePageIs('/user')
        ;
    }

    public function testDelete()
    {   
        $userToDelete = User::where('email', 'Nowak@gmail.com')->first();

        $this
        ->actingAs($this->user)
        ->visit('/')
        ->click('Pracownicy')
        ->seePageis('/user')
        ->call("DELETE", "/user/$userToDelete->id");
    }
}
