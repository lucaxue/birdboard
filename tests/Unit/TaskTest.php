<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_path()
    {
        $task = Task::factory()->create();

        $this->assertEquals(
            "/projects/{$task->project->id}/tasks/{$task->id}",
            $task->path()
        );
    }

    /** @test */
    public function it_belongs_to_a_project()
    {
        $task = Task::factory()->create();

        $this->assertInstanceOf(Project::class, $task->project);
    }

    /** @test */
    public function it_can_be_completed()
    {
        $task = Task::factory()->create();

        $this->assertFalse($task->completed);

        $task->complete();

        $this->assertTrue($task->fresh()->completed);
    }

    /** @test */
    public function it_can_be_marked_as_incomplete()
    {
        $task = Task::factory()->create(['completed' => true]);

        $this->assertTrue($task->completed);

        $task->incomplete();

        $this->assertFalse($task->fresh()->completed);
    }
}
