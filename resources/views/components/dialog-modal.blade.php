@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-4">
<<<<<<< HEAD
        <div class="text-lg font-medium text-gray-900">
            {{ $title }}
        </div>

        <div class="mt-4 text-sm text-gray-600">
=======
        <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ $title }}
        </div>

        <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
>>>>>>> ec6237d (Third Week of Assignment small changes)
            {{ $content }}
        </div>
    </div>

<<<<<<< HEAD
    <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-end">
=======
    <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 dark:bg-gray-800 text-end">
>>>>>>> ec6237d (Third Week of Assignment small changes)
        {{ $footer }}
    </div>
</x-modal>
