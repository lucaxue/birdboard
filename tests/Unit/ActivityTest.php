<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_user()
    {
        $project = Project::factory()->create();

        $this->assertEquals(
            $project->owner->id,
            $project->activity->first()->user->id
        );
    }
}
