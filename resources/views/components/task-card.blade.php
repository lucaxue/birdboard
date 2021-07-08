@props(['task'])

<form method="POST" action="{{ $task->path() }}">
	@method('PATCH')
	@csrf

	<x-card class="py-4 pr-6 flex items-center justify-between">
		<input name="body"
			class="focus:outline-none border-l-4 border-green-300 py-1 pl-3 {{ $task->completed ? 'text-gray-400' : '' }}"
			value="{{ $task->body }}">
		<div class="flex items-center gap-4">
			<label for="completed" class="text-gray-400">Due Someday</label>
			<input type="checkbox" name="completed" class="border-gray-300 rounded-sm"
				{{ $task->completed ? 'checked' : ''}} onChange="this.form.submit()" />
		</div>
	</x-card>
</form>