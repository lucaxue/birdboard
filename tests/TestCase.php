<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected User $user;

    protected function signIn(?User $user = null)
    {
        $this->actingAs($signedInUser = $user ?? User::factory()->create());
        return $signedInUser;
    }
}
