@props(['project'])

<x-card class="py-5 grid gap-4">
	<h1 class="truncate border-l-4 py-2 px-6 border-blue-400 text-2xl">
		Invite a teammate!
	</h1>
	<form class="flex items-center gap-2 px-6" method="POST" action="{{ $project->path() }}/invitations">
		@csrf

		<input
			class="shadow appearance-none @error('email') border border-red-500 @else border-none @enderror rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-4 focus:ring-blue-300"
			name="email" type="email" placeholder="Email">
		<button type="submit">➕</button>
	</form>
	@error('email')
	<p class="text-sm text-red-500 mx-6">{{ $message }}</p>
	@enderror
</x-card>