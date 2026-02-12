@extends('app')

@section('title', 'Download Ficheiros')

@section('scripts')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const imageUrls = [
                'https://images.unsplash.com/photo-1555881400-74d7acaacd8b?ixlib=rb-4.1.0&q=85&fm=jpg&crop=entropy&cs=srgb&w=1470',
                'https://images.unsplash.com/photo-1585208798174-6cedd86e019a?ixlib=rb-4.1.0&q=85&fm=jpg&crop=entropy&cs=srgb&w=1470',
                'https://images.unsplash.com/photo-1501927023255-9063be98970c?ixlib=rb-4.1.0&q=85&fm=jpg&crop=entropy&cs=srgb&w=1470',
                'https://images.unsplash.com/photo-1544121415-acc4ed3e785c?ixlib=rb-4.1.0&q=85&fm=jpg&crop=entropy&cs=srgb&w=1470',
                'https://images.unsplash.com/photo-1555881400-69a2384edcd4?ixlib=rb-4.1.0&q=85&fm=jpg&crop=entropy&cs=srgb&w=1470',
                'https://images.unsplash.com/photo-1513377888081-794d8e958972?ixlib=rb-4.1.0&q=85&fm=jpg&crop=entropy&cs=srgb&w=1470',
                'https://images.unsplash.com/photo-1558102400-72da9fdbecae?ixlib=rb-4.1.0&q=85&fm=jpg&crop=entropy&cs=srgb&w=1470',
            ];

            const randomImageUrl = imageUrls[Math.floor(Math.random() * imageUrls.length)];
            const wrapper = document.getElementById('wrapper');
            wrapper.style.backgroundImage = `url('${randomImageUrl}')`;
            wrapper.style.backgroundSize = 'cover';
            wrapper.style.backgroundPosition = 'center';
        });
    </script>
@endsection

@section('content')

    <div>
        <h1 class="text-xl md:text-2xl font-bold mb-1">Os seus ficheiros estão prontos</h1>
        <p class="text-sm text-[#616161] mb-4">Esta transferência está segura e pronta para download.</p>
    </div>

    <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0">
        <div class="hidden md:flex md:w-1/4 h-auto">
        @if (!empty($qr_code))
            <img src="{{ $qr_code }}" alt="QR Code" class="w-full h-full object-contain object-top">
        @else
            <div class="w-full h-44 rounded-lg bg-[#2D6FB4] bg-gradient-to-tl from-[#2D6FB4] to-[#255a8a]  items-center justify-center flex">
            @if (count($attachments) == 1)
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-18 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-18 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 13.5 3 3m0 0 3-3m-3 3v-6m1.06-4.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                </svg>
            @endif
            </div>
        @endif
        </div>
        <div class="w-full md:w-3/4">
            <h2 class="text-md md:text-xl font-semibold mt-0 mb-2">A HBR enviou-lhe {{ count($attachments) }} ficheiros</h2>
            <div class="mb-2 text-sm text-[#757575]">
                <div class="inline-flex items-center space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                    </svg>

                    @php
                        $totalSize = array_sum(array_column($attachments, 'size'));
                        $formattedSize = number_format($totalSize / (1024 * 1024), 2) . ' MB';
                    @endphp

                    <span>{{ $formattedSize }}</span>&nbsp;&bull;&nbsp;{{ count($attachments) }} ficheiros
                </div>
            </div>
            <div class="mb-4 text-sm font-semibold text-[#2D6FB4]">
                <div class="inline-flex items-center space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>O link expira {{ $expires_at }}</span>
                </div>
            </div>

            <div class="bg-[#EEEEEE] p-4 rounded-lg  mb-4 text-sm text-[#616161]">
                {{ $message }}
            </div>

            <a href="/f/{{ $uuid }}/download" class="flex w-full items-center justify-center px-4 py-2 bg-[#2D6FB4] text-white rounded-lg hover:bg-[#255a8a] focus:outline-none focus:ring-2 focus:ring-[#2D6FB4] focus:ring-offset-2 shadow-sm">
                Decarregar Ficheiros
            </a>
        </div>
    </div>
    <div class="mt-4 md:mt-8">
        <h3 class="text-sm md:text-md uppercase font-semibold mb-4 bg-[#EEEEEE] p-4 rounded-lg">Ficheiros incluídos:</h3>
        <ul class="space-y-2">
            @foreach($attachments as $attachment)
                <li class="flex flex-row-reverse md:flex-row justify-between items-center p-3 md:p-2 border-b border-[#EEEEEE]">
                    <div class="flex flex-col space-y-1 flex-1 md:w-auto">
                        <p class="text-sm">{{ $attachment['name'] }}</p>
                        <p class="text-sm text-[#616161]">
                            @php
                                $sizeInMB = number_format($attachment['size'] / (1024 * 1024), 2);
                            @endphp
                            {{ $sizeInMB }} MB
                        </p>
                    </div>

                    @php
                        $fileName = basename($attachment['path']);
                    @endphp

                    <a href="/f/{{ $uuid }}/download/{{ $fileName }}" class="mr-4 md:mr-0 md:ml-4 text-[#2D6FB4] self-center" target="_blank" rel="noopener noreferrer" title="Descarregar">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

@endsection
