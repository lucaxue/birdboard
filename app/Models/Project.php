<?php

namespace App\Models;

use App\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, RecordsActivity;

    protected $fillable = [
        'title', 'description', 'notes'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function path()
    {
        return "/projects/{$this->id}";
    }

    public function addTask($task)
    {
        return $this->tasks()->create($task);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class)->latest('updated_at');
    }

    public function activity()
    {
        return $this->hasMany(Activity::class, 'project_id')->latest();
    }

    public function invite(User $user)
    {
        return $this->members()->attach($user);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')->withTimestamps();
    }
}
