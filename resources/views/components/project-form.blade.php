@props([
'heading',
'project' => null,
'submit'
])

<form method="POST" action="{{ $project ? $project->path() : route('projects.index') }}"
	{{ $attributes->merge(['class' => "lg:w-1/2 md:w-2/3 w-full mx-auto grid bg-white py-10 px-12 rounded-lg shadow-sm gap-4"]) }}>
	@method($project ? 'PATCH' : 'POST')
	@csrf

	{{ $slot }}

	<div>
		<label class="block text-gray-700 text-sm font-bold mb-2" for="title">Title</label>
		<input
			class="shadow appearance-none @error('title') border border-red-500 @enderror rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-4 focus:ring-blue-300"
			name="title" placeholder="Title" value="{{ $project->title ?? '' }}" required>

		@error('title')
		<p class="text-red-500 text-xs italic">{{ $message }}</p>
		@enderror
	</div>

	<div>
		<label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>

		<textarea name="description"
			class="shadow @error('description') border border-red-500 @else border-none @enderror rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-4 focus:ring-blue-300"
			rows="10" placeholder="Description" required>{{ $project->description ?? '' }}</textarea>

		@error('description')
		<p class="text-red-500 text-xs italic">{{ $message }}</p>
		@enderror
	</div>

	<div class="flex gap-4 justify-end w-full">
		<a href="{{ $project ? $project->path() : route('projects.index') }}"
			class="bg-gray-400 text-center rounded py-2 px-4 font-bold text-white">Cancel</a>

		<button type="submit" class="bg-blue-400 rounded py-2 px-4 font-bold text-white">{{ $submit }}</button>
	</div>
</form>