<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_instructor(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);

        $this->assertTrue($user->isInstructor());
        $this->assertFalse($user->isVisitor());
    }

    public function test_user_can_be_visitor(): void
    {
        $user = User::factory()->create(['role' => 'visitor']);

        $this->assertTrue($user->isVisitor());
        $this->assertFalse($user->isInstructor());
    }

    public function test_user_has_fillable_attributes(): void
    {
        $user = new User;

        $this->assertEquals(
            ['name', 'email', 'password', 'phone', 'role'],
            $user->getFillable()
        );
    }

    public function test_user_has_hidden_attributes(): void
    {
        $user = new User;

        $this->assertEquals(
            ['password', 'remember_token'],
            $user->getHidden()
        );
    }
}
