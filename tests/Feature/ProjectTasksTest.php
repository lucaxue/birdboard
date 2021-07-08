<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function guests_cannot_add_tasks_to_projects()
    {
        $project = Project::factory()->create();

        $this->post($project->path() . '/tasks')
            ->assertRedirect(route('login'));
    }
    /** @test */
    public function guests_cannot_update_tasks()
    {
        $task = Task::factory()->create();

        $this->patch($task->path(), [
            'body' => 'Changed',
            'completed' => true
        ])->assertRedirect(route('login'));
    }

    /** @test */
    public function a_user_can_add_tasks_to_their_project()
    {
        $project = Project::factory()->create();

        $this->actingAs($project->owner)
            ->post($project->path() . '/tasks', ['body' => 'Test task'])
            ->assertRedirect($project->path());

        $this->assertDatabaseHas('tasks', ['body' => 'Test task']);

        $this->get($project->path())->assertSee('Test task');
    }

    /** @test */
    public function a_user_cannot_add_tasks_to_projects_they_are_not_part_of()
    {
        $this->signIn();
        $project = Project::factory()->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test task'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Test task']);
    }

    /** @test */
    public function a_user_can_update_tasks_on_their_project()
    {
        $project = Project::factory()

            ->hasTasks(1)
            ->create();

        $this->actingAs($project->owner)
            ->patch(
                $project->tasks->first()->path(),
                $attributes = ['body' => 'Changed']
            )->assertRedirect($project->path());

        $this->assertDatabaseHas('tasks', $attributes);

        $this->get($project->tasks->first()->path())
            ->assertSee($attributes['body']);
    }

    /** @test */
    public function a_user_can_complete_tasks_on_their_project()
    {
        $project = Project::factory()
            ->hasTasks(1)
            ->create();

        $this->actingAs($project->owner)
            ->patch(
                $project->tasks->first()->path(),
                $attributes = ['completed' => true]
            )->assertRedirect($project->path());

        $this->assertDatabaseHas('tasks', $attributes);
    }

    /** @test */
    public function a_user_can_mark_tasks_as_incomplete_on_their_project()
    {
        $this->withoutExceptionHandling();
        $project = Project::factory()
            ->hasTasks(1, ['completed' => true])
            ->create();

        $this->actingAs($project->owner)
            ->patch(
                $project->tasks->first()->path(),
                $attributes = ['completed' => false]
            )->assertRedirect($project->path());

        $this->assertDatabaseHas('tasks', $attributes);
    }



    /** @test */
    public function a_user_cannot_update_tasks_they_are_not_part_of()
    {
        $this->signIn();
        $project = Project::factory()->hasTasks(1)->create();

        $this->patch($project->tasks->first()->path(), [
            'body' => 'Changed',
            'completed' => true
        ])->assertStatus(403);

        $this->assertDatabaseMissing('tasks', [
            'body' => 'Changed',
            'completed' => true
        ]);;
    }

    /** @test */
    public function a_task_requires_a_body()
    {
        $project = Project::factory()->create();

        $task = Task::factory()->raw(['body' => null]);

        $this->actingAs($project->owner)
            ->post($project->path() . '/tasks', $task)
            ->assertSessionHasErrors('body');
    }
}
