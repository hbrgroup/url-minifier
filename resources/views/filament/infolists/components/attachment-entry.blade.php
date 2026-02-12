<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    <div {{ $getExtraAttributeBag() }}>
        @foreach($getState() as $attachment)
            <div class="flex items-center space-x-2 text-sm text-gray-900 dark:text-white  p-2">
                <x:filament::icon icon="heroicon-o-document-text" width="20" class="text-gray-500" />
                <x:filament::link :href="$attachment['url']" target="_blank" rel="noopener noreferrer">
                    {{ $attachment['name'] }}
                </x:filament::link>
                &bull;&nbsp;<span class="text-gray-500">{{ number_format($attachment['size'] / (1024 * 1024), 2) }} MB</span>
            </div>
        @endforeach
    </div>
</x-dynamic-component>
