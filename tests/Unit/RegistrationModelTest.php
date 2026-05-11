<?php

namespace Tests\Unit;

use App\Models\Masterclass;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_has_fillable_attributes(): void
    {
        $registration = new Registration;

        $this->assertEquals(
            ['user_id', 'masterclass_id'],
            $registration->getFillable()
        );
    }

    public function test_registration_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $masterclass = Masterclass::factory()->create();
        $registration = Registration::factory()->create([
            'user_id' => $user->id,
            'masterclass_id' => $masterclass->id,
        ]);

        $this->assertInstanceOf(User::class, $registration->user);
        $this->assertEquals($user->id, $registration->user->id);
    }

    public function test_registration_belongs_to_masterclass(): void
    {
        $user = User::factory()->create();
        $masterclass = Masterclass::factory()->create();
        $registration = Registration::factory()->create([
            'user_id' => $user->id,
            'masterclass_id' => $masterclass->id,
        ]);

        $this->assertInstanceOf(Masterclass::class, $registration->masterclass);
        $this->assertEquals($masterclass->id, $registration->masterclass->id);
    }
}
