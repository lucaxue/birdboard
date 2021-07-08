<x-app-layout>
	<x-slot name="header">
		<div class="flex w-full items-center justify-between h-12">
			<h2 class="font-semibold text-xl text-gray-500 leading-tight">
				Edit Project
			</h2>
		</div>
	</x-slot>
	<x-project-form :project="$project" submit="Update">
		<h1 class="text-3xl font-bold text-center">
			Made a mistake?
		</h1>
	</x-project-form>
</x-app-layout>