<x-app-layout>
	<x-slot name="header">
		<div class="flex w-full items-center justify-between h-12">
			<h2 class="font-semibold text-xl text-gray-500 leading-tight">
				Create Project
			</h2>
		</div>
	</x-slot>
	<x-project-form submit="Create">
		<h1 class="text-3xl font-bold text-center">
			Let's start a new project
		</h1>
	</x-project-form>
</x-app-layout>