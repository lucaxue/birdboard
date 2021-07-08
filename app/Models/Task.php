<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Project;
use App\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory, RecordsActivity;

    protected $fillable = ['body', 'completed'];

    protected $touches = ['project'];

    protected $casts = ['completed' => 'bool'];

    protected static $recordableEvents = ['created', 'deleted'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function path()
    {
        return $this->project->path() . '/tasks/' . $this->id;
    }

    public function complete(): void
    {
        $this->update(['completed' => true]);
        $this->recordActivity('completed_task');
    }

    public function incomplete(): void
    {
        $this->update(['completed' => false]);
        $this->recordActivity('incompleted_task');
    }
}
