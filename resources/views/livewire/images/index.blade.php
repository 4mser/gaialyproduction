<section class="overflow-hidden">
    @if (count($images) > 0)
        <div class="grid w-full grid-cols-8 justify-items-stretch gap-2 overflow-y-auto p-2" style="height:34vh">
            @foreach ($images as $image)
                <div class="relative h-32 bg-black">
                    <div class="absolute bottom-0 left-0 right-0 top-0 flex">
                        <img class="cursor-pointer" onclick="centerMap('{!! base64_encode($image->geom) !!}')" title="{{ __('Center map') }}" src="{{ url('storage/' . str_replace('layers/', 'layers/preview_', $image->file_name)) }}" alt="image">
                    </div>
                    <div class="absolute bottom-0 right-0">
                        <button onclick="window.open('{{ url('storage/' . str_replace('layers/', 'layers/preview_', $image->file_name)) }}', '_blank')" class="bg-primary px-3 py-2 font-bold text-white hover:bg-blue-300" title="{{ __('View Image') }}">
                            <svg xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3 w-3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>

                        </button>
                        <button onclick="download('{{ url('storage/' . str_replace('layers/', 'layers/preview_', $image->file_name)) }}')" class="bg-primary px-3 py-2 font-bold text-white hover:bg-blue-300" title="{{ __('Download Image') }}">
                            <svg xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3 w-3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>

                        </button>
                        <button onclick="go('{{ route('map.image', ['id' => $image->id]) }}')" class="bg-primary px-3 py-2 font-bold text-white hover:bg-blue-300" title="{{ __('Edit Image') }}">
                            <svg xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3 w-3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                            </svg>
                        </button>
                        <button <button title="{{ __('Delete Layer') }}" onClick="confirm('{{ $image->id }}','{{ __('Are you sure you want to remove this layer?') }}','deleteLayer')" class="bg-primary px-3 py-2 font-bold text-white hover:bg-blue-300">
                            <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3 w-3">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>

                        </button>
                    </div>
                    <div class="absolute left-0 right-0 flex">
                        <p class="bg-black p-1 text-xs text-white">{{ $image->name }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <h2 class="p-6 text-center font-bold text-gray-500"> {{ __('No results') }}</h2>
    @endif
</section>


<script>
    const go = url => {
        // go to url
        window.location.href = url;
    }

    const download = url => {
        // save image in local
        const link = document.createElement('a');
        link
            .setAttribute('download', '');
        link
            .setAttribute('href', url);
        link
            .click();


    }

    function centerMap(coords) {

        JSON.parse(coords).forEach(element => {
            bounds.push([element[1], element[0]]);
        });
        bounds = L.latLngBounds(bounds);
        map.fitBounds(bounds);

    }
</script>
