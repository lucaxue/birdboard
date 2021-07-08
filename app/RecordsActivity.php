<?php

namespace App;

use App\Models\Activity;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait RecordsActivity
{
	public static function bootRecordsActivity()
	{
		foreach (static::recordableEvents() as $event) {
			static::$event(function ($model) use ($event) {
				$model->recordActivity($model->activityDescription($event));
			});
		}
	}

	protected function activityDescription($event)
	{
		return Str::slug("$event " . class_basename($this), '_');
	}

	protected static function recordableEvents()
	{
		if (isset(static::$recordableEvents)) {
			return static::$recordableEvents;
		}
		return ['created', 'updated'];
	}

	public function activity()
	{
		return $this->morphMany(Activity::class, 'subject')->latest();
	}

	public function recordActivity(string $description)
	{
		return $this->activity()->create([
			'user_id' => $this->activityOwner()->id,
			'description' => $description,
			'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project->id,
			'changes' => $this->activityChanges()
		]);
	}

	protected function activityOwner()
	{
		return auth()->check()
			? auth()->user()
			: ($this->project ?? $this)->owner;
	}

	protected function activityChanges()
	{
		if ($this->wasChanged()) {
			return [
				'before' => Arr::except(
					array_diff($this->getOriginal(), $this->getAttributes()),
					'updated_at'
				),
				'after' => Arr::except($this->getChanges(), 'updated_at')
			];
		}
	}
}
