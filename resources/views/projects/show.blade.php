<x-app-layout>
	<x-slot name="header">
		<div class="flex w-full items-center justify-between h-12">
			<div class="flex gap-4 items-center">
				<h2 class="font-semibold text-xl text-gray-500 leading-tight">
					<a href="{{ route('projects.index') }}" class="hover:underline">My
						Projects</a>/{{ $project->title }}
				</h2>
				<a href="#" class="bg-blue-400 shadow-sm rounded-lg text-white px-8 py-2 hover:bg-blue-500 font-bold">
					Add Task
				</a>
			</div>
			<div class="flex gap-4 items-center">
				<div class="flex gap-2 items-center">
					@foreach ($project->members as $member)
					<img class="rounded-full shadow" src="{{ $member->avatar }}" alt="{{ $member->name }}" width="30"
						height="30" />
					@endforeach
					<img class="rounded-full shadow" src="{{ $project->owner->avatar }}"
						alt="{{ $project->owner->name }}" width="30" height="30" />
				</div>
				<a href="{{ $project->path() }}/edit"
					class="bg-blue-400 shadow-sm rounded-lg text-white px-8 py-2 hover:bg-blue-500 font-bold">
					Edit Project
				</a>
			</div>
		</div>
	</x-slot>

	<div class="lg:flex w-full mt-3 gap-3">
		<div class="grid gap-6 lg:w-2/3">
			<div>
				<h1 class="h-10 font-semibold text-xl text-gray-500 leading-tight">Tasks</h1>
				<div class="grid gap-3">

					<form method="POST" action="{{ $project->path() }}/tasks" class="w-full">
						@csrf
						<x-card class="py-4 pr-6 flex items-center justify-between">
							<input name="body" class="focus:outline-none border-l-4 border-gray-300 py-1 pl-3 w-full"
								placeholder="Add new task...">

							<input type="submit" hidden />
						</x-card>
					</form>

					@forelse ($project->tasks as $task)
						<x-task-card :task="$task" />
					@empty
						<p>No tasks...</p>
					@endforelse
				</div>
			</div>
			<div>
				<form method="POST" action="{{ $project->path() }}">
					@method('PATCH')
					@csrf

					<label for="notes" class="h-10 font-semibold text-xl text-gray-500 leading-tight">General
						Notes</label>
					<textarea name="notes"
						class="shadow @error('notes') border border-red-500 @else border-none @enderror rounded w-full p-6 text-gray-700 mt-4 focus:outline-none focus:ring-4 focus:ring-blue-300"
						rows="8" placeholder="Anything you want to jot down?">{{ $project->notes }}</textarea>

					@error('notes')
					<p class="text-red-500 text-xs italic">{{ $message }}</p>
					@enderror
					<button type="submit"
						class="bg-blue-400 rounded shadow-sm font-bold text-white px-8 py-2 hover:bg-blue-500">Save</button>
				</form>
			</div>
		</div>

		<div class="lg:w-1/3 flex flex-col gap-3">
			<x-project-card :project="$project" class="mt-10" />
			@can('manage', $project)
			<x-invite-form :project="$project"/>
			@endcan
		</div>
	</div>

	<x-slot name="sidebar">
		<x-activity-bar :project="$project"/>
	</x-slot>

</x-app-layout>