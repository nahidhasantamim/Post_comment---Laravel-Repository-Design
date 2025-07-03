<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-center text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $blog->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                        <a href="#">
                            <img class="rounded-lg mt-3 mx-auto" src="{{ asset('/storage/'.$blog->image) }}" alt="" />
                        </a>
                        <div class="p-5">
                            {{-- <a href="#">
                                <h5 class="mb-2 text-center text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $blog->title }}</h5>
                            </a> --}}
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                                {{ $blog->description }}
                            </p>
                            

                            <hr class="my-5">

                            <a href="{{route('blogs.edit', $blog)}}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Edit
                            </a>

                            <form
                                action="{{ route('blogs.destroy', $blog) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this blog?');"
                                class="inline"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800"
                                >
                                    Delete
                                </button>
                            </form>

                        </div>
                    </div>
                    
                    <div class="rounded-lg shadow-sm my-5">
                        <form
                            action="{{ route('comments.store') }}"
                            method="POST"
                            enctype="multipart/form-data"
                            class="space-y-6">
                            @csrf

                            <input type="hidden" name="blog_id" value="{{ $blog->id }}">

                            <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your comment</label>
                            <textarea id="message" name="body" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write your thoughts here..."></textarea>
                        
                            <button
                                type="submit"
                                class="btn-sm text-sm py-1 px-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Save
                            </button>
                        </form>                 
                    </div>
                    
                    <div class="rounded-lg shadow-sm my-5">
                        <dl class="max-w-md text-gray-900 divide-y divide-gray-200 dark:text-white dark:divide-gray-700">
                            @foreach ($comments as $comment)
                            <div class="flex flex-col pb-3" id="comment-{{ $comment->id }}">

                                <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">
                                {{ $comment->user->name }}
                                </dt>
                                <dd class="text-lg font-semibold mb-2">{{ $comment->body }}</dd>

                                <button
                                type="button"
                                onclick="toggleEdit({{ $comment->id }})"
                                class="px-2 py-1 w-20 text-sm font-medium text-white bg-yellow-700 rounded-lg hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-blue-300"
                                >
                                Edit
                                </button>

                               <form
                                    action="{{ route('comments.destroy', $comment) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure?');"
                                    class="inline">

                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="mt-2 px-2 py-1 w-20 text-sm font-medium text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300">Delete</button>
                                </form>

                                <div
                                id="edit-form-{{ $comment->id }}"
                                class="hidden mt-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg"
                                >
                                <form
                                    action="{{ route('comments.update', $comment) }}"
                                    method="POST"
                                    class="space-y-4"
                                >
                                    @csrf
                                    @method('PUT')

                                    <textarea
                                    name="body"
                                    rows="3"
                                    required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring focus:ring-indigo-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    >{{ $comment->body }}</textarea>

                                    <div class="flex space-x-2">
                                    <button
                                        type="submit"
                                        class="px-2 py-1 text-sm bg-green-600 text-white rounded hover:bg-green-700 focus:outline-none"
                                    >
                                        Update
                                    </button>
                                    <button
                                        type="button"
                                        onclick="toggleEdit({{ $comment->id }})"
                                        class="px-2 py-1 text-sm bg-gray-400 text-white rounded hover:bg-gray-500 focus:outline-none"
                                    >
                                        Cancel
                                    </button>
                                    </div>
                                </form>
                                </div>
                            </div>
                            @endforeach
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function toggleEdit(id) {
            const form = document.getElementById(`edit-form-${id}`);
            form.classList.toggle('hidden');
        }
    </script>
</x-app-layout>
