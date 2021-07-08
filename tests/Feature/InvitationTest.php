<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_invite_other_users_to_their_project()
    {
        $project = Project::factory()->create();
        $userToInvite = User::factory()->create();

        $this->actingAs($project->owner)
            ->post($project->path() . '/invitations', [
                'email' => $userToInvite->email
            ])->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($userToInvite));

        $this->assertDatabaseHas('project_members', [
            'project_id' => $project->id,
            'user_id' => $userToInvite['id']
        ]);
    }

    /** @test */
    public function a_user_cannot_invite_non_registered_users()
    {
        $project = Project::factory()->create();

        $this->actingAs($project->owner)
            ->post($project->path() . '/invitations', [
                'email' => 'not.a.registered.user@example.com'
            ])->assertSessionHasErrors([
                'email' => 'The user you are inviting is not registered to Birdboard.'
            ]);
    }

    /** @test */
    public function a_user_cannot_invite_other_users_to_projects_they_are_not_part_of()
    {
        $this->signIn();
        $project = Project::factory()->create();

        $this->post($project->path() . '/invitations', [])
            ->assertStatus(403);
    }

    /** @test */
    public function a_project_member_cannot_invite_users_to_the_project()
    {
        $project = Project::factory()->hasMembers(1)->create();

        $this->actingAs($project->members->first())
            ->post($project->path() . '/invitations', [])
            ->assertStatus(403);
    }

    /** @test */
    public function invited_users_can_update_project()
    {
        $project = Project::factory()->hasMembers(1)->create();

        $this->actingAs($project->members->first())
            ->post(
                $project->path() . '/tasks',
                $task = ['body' => 'test task']
            )->assertRedirect($project->path());

        $this->assertDatabaseHas('tasks', $task);
        $this->get($project->path())
            ->assertSee($task['body']);
    }
}
