<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageProjectsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_manage_a_project()
    {
        $project = Project::factory()->create();

        $this->get('/projects')
            ->assertRedirect(route('login'));

        $this->get('/projects/create')
            ->assertRedirect(route('login'));

        $this->get($project->path())
            ->assertRedirect(route('login'));

        $this->get($project->path() . '/edit')
            ->assertRedirect(route('login'));

        $this->post('/projects', $project->toArray())
            ->assertRedirect(route('login'));

        $this->delete($project->path())
            ->assertRedirect(route('login'));

        $this->patch($project->path(), [])
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function a_user_can_create_a_project()
    {
        $user = $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $this->followingRedirects()
            ->post('/projects', $attributes = Project::factory()->raw())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function a_user_can_delete_their_project()
    {
        $project = Project::factory()->create();

        $this->actingAs($project->owner)
            ->delete($project->path())
            ->assertRedirect(route('projects.index'));

        $this->assertNull($project->fresh());

        $this->get(route('projects.index'))
            ->assertDontSee($project->title);
    }

    /** @test */
    public function a_user_can_update_their_project()
    {
        $project = Project::factory()->forOwner()->create();

        $this->actingAs($project->owner)
            ->get($project->path() . '/edit')
            ->assertStatus(200);

        $this->patch($project->path(), $attributes = [
            'title' => 'Updated title',
            'description' => 'Updated description',
        ])->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);

        $this->get($project->path())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description']);
    }

    /** @test */
    public function a_user_can_update_the_notes_of_their_project()
    {
        $project = Project::factory()->forOwner()->create();

        $this->actingAs($project->owner)
            ->get($project->path());

        $this->patch($project->path(), $attributes = [
            'notes' => 'Updated notes'
        ])->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);

        $this->get($project->path())
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function a_user_can_view_their_project()
    {
        $project  = Project::factory()->forOwner()->create();

        $this->actingAs($project->owner)
            ->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    public function a_user_can_view_all_projects_they_have_been_invited_to_on_their_dashboard()
    {
        $user = $this->signIn();

        $project = Project::factory()->create();
        $project->invite($user);

        $this->get(route('projects.index'))->assertSee($project->title);
    }

    /** @test */
    public function a_user_cannot_view_projects_they_are_not_part_of()
    {
        $this->signIn();

        $project = Project::factory()->create();

        $this->get($project->path())->assertStatus(403);
    }

    /** @test */
    public function a_user_cannot_update_projects_they_are_not_part_of()
    {
        $this->signIn();

        $project = Project::factory()->create();

        $this->get($project->path() . '/edit')->assertStatus(403);
        $this->patch($project->path(), [])->assertStatus(403);
    }

    /** @test */
    public function a_user_cannot_delete_projects_they_are_not_part_of()
    {
        $this->signIn();

        $project = Project::factory()->create();

        $this->delete($project->path())->assertStatus(403);
    }

    /** @test */
    public function a_project_member_cannot_delete_the_project()
    {
        $project = Project::factory()->hasMembers(1)->create();

        $this->actingAs($project->members->first())
            ->delete($project->path())
            ->assertStatus(403);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->signIn();

        $attributes = Project::factory()->raw([
            'title' => null
        ]);

        $this->post('/projects', $attributes)
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->signIn();

        $attributes = Project::factory()->raw([
            'description' => null
        ]);

        $this->post('/projects', $attributes)
            ->assertSessionHasErrors('description');
    }
}
