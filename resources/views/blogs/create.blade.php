<x-app-layout>
    <x-slot name="header">
        <h2
            class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Blog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form
                        action="{{ route('blogs.store') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-white">Title</label>
                            <input
                                type="text"
                                name="title"
                                id="title"
                                value="{{ old('title') }}"
                               
                                class="mt-1 block w-full text-gray-900 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('title')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-white">Description</label>
                            <textarea
                                name="description"
                                id="description"
                                rows="6"
                               
                                class="mt-1 block w-full text-gray-900 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 dark:text-white">Featured Image</label>
                            <input
                                type="file"
                                name="image"
                                id="image"
                                accept="image/*"
                                class="mt-1 block w-full text-gray-700 dark:text-white">
                                @error('image')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                        </div>

                        <div class="pt-4">
                            <button
                                type="submit"
                                class="w-full inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                                Create Blog
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>