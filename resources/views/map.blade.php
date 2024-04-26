<x-app-layout>
    <section class="flex h-screen flex-col overflow-hidden border">
        <livewire:map.topbar />
        <div class="flex h-full border">
            <div class="h-full w-full overflow-auto">
                <div class=""
                     style="background-color:#AAD3DF;">
                    <livewire:map.map />
                </div>
                <div>
                    <livewire:images.index />
                </div>
            </div>
            <aside class="h-full border">
                <livewire:map.sidebar />
            </aside>
        </div>
    </section>
</x-app-layout>
