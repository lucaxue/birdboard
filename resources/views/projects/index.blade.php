<x-app-layout>
    <x-slot name="header">
        <div class="flex w-full items-center justify-between h-12">
            <h2 class="font-semibold text-xl text-gray-500 leading-tight">
                My Projects
            </h2>
            <a href="{{ route('projects.create') }}"
                class="bg-blue-400 shadow-sm rounded-lg text-white px-8 py-2 hover:bg-blue-500 font-bold">
                Add Project
            </a>
        </div>
    </x-slot>
    <div class="w-full grid lg:grid-cols-3 md:grid-cols-2 gap-4">
        @forelse ($projects as $project)
        <x-project-card :project="$project" />
        @empty
        <p class="text-gray-500">No projects</p>
        @endforelse
    </div>
</x-app-layout>