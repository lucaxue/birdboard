<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_has_projects()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(
            Collection::class,
            $user->projects
        );
    }

    /** @test */
    public function it_has_all_projects()
    {
        // Given I have a user with a project
        $project = Project::factory()->create();
        $user = $project->owner;

        // Then the user should have 1 total project
        $this->assertCount(1, $user->allProjects());

        // When he is not invited to another project
        $project = Project::factory()->hasMembers(1)->create();

        // Then the user should still have 1 total project
        $this->assertCount(1, $user->allProjects());

        // When he is invited to the other project
        $project->invite($user);

        // Then the user should have 2 total projects
        $this->assertCount(2, $user->allProjects());
    }
}
