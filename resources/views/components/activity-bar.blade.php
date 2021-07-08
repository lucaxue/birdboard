@props(['project'])

<div class="w-full bg-gray-50 h-full p-8 shadow mt-0.5">
	<h2 class="font-semibold text-xl text-gray-500 leading-tight mb-2">
		Latest Activity
	</h2>
	<ul>
		@foreach ($project->activity as $activity)
		<li class="leading-loose text-sm font-semibold truncate">
			<x-activity-description :activity="$activity" />

			<span class="text-gray-500 text-xs"> · {{ $activity->created_at->diffForHumans(null, true) }}</span>
		</li>
		@endforeach
	</ul>
</div>