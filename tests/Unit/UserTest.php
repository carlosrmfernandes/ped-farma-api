<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    public function testCreateUser()
    {
        \App\Models\User::create([
            "email" => "carlos@gmail.com",
            "password" => bcrypt(1234),
            "active" => 1,
            "is_provider" => 1,
            "is_admin" => 1,
            "last_name" => "Fernandes",
            "name" => "Carlos",
        ]);

        $this->assertDatabaseHas('users',['name'=>'Carlos']);
        $this->assertTrue(true);
    }

}
