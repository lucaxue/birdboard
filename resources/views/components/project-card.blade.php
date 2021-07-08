@props(['project'])

<x-card {{ $attributes->merge(['class' => 'grid gap-6 py-6 relative']) }}>
	<h1 class="truncate border-l-4 py-2 px-6 border-blue-400 text-2xl">
		<a href="{{ $project->path() }}">
			{{ $project->title }}
		</a>
	</h1>
	<p class="overflow-ellipsis overflow-hidden h-24 text-gray-500 px-6 mb-6">
		{{ $project->description }}
	</p>
	@can('delete', $project)
	<form class="absolute right-6 bottom-4" method="POST" action="{{ $project->path() }}">
		@method('DELETE')
		@csrf
		<button type="submit">🗑</button>
	</form>
	@endcan
</x-card>