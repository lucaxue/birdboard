<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TriggerRecordActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_a_project()
    {
        $project = Project::factory()->create();

        $this->assertCount(1, $project->activity);
        $this->assertEquals('created_project', $project->activity->first()->description);
        $this->assertEquals(null, $project->activity->last()->changes);
    }

    /** @test */
    public function updating_a_project()
    {
        $project = Project::factory()->create();
        $originalNotes = $project->notes;

        $project->update(['notes' => 'Updated notes']);

        $this->assertCount(2, $project->activity);
        $this->assertEquals('updated_project', $project->activity->last()->description);
        $this->assertEquals([
            'before' => ['notes' => $originalNotes],
            'after' => ['notes' => 'Updated notes']
        ], $project->activity->last()->changes);
    }

    /** @test */
    public function creating_a_new_task()
    {
        $project = Project::factory()->create();

        $project->addTask(Task::factory()->raw());

        $this->assertCount(2, $project->activity);
        $this->assertEquals(
            'created_task',
            $project->activity->last()->description
        );
        $this->assertInstanceOf(
            Task::class,
            $project->activity->last()->subject
        );
    }

    /** @test */
    public function completing_a_task()
    {
        $project = Project::factory()->hasTasks(1)->create();

        $project->tasks->first()->complete();

        $this->assertCount(3, $project->activity);
        $this->assertEquals(
            'completed_task',
            $project->activity->last()->description
        );
        $this->assertInstanceOf(
            Task::class,
            $project->activity->last()->subject
        );
    }

    /** @test */
    public function incompleting_a_task()
    {
        $project = Project::factory()
            ->hasTasks(1, ['completed' => true])
            ->create();

        $project->tasks->first()->incomplete();

        $this->assertCount(3, $project->activity);
        tap($project->activity->last(), function ($activity) {
            $this->assertEquals('incompleted_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
        });
    }

    /** @test */
    public function deleting_a_task()
    {
        $this->withoutExceptionHandling();
        $project = Project::factory()->hasTasks(1)->create();

        $project->tasks->first()->delete();

        $this->assertCount(3, $project->activity);
        $this->assertEquals(
            $project->activity->last()->description,
            'deleted_task'
        );
    }
}
