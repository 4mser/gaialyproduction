<section>
    <div class="m-2">
        <!--actual component start-->
        <div x-data="setup()">
            <ul class="flex items-center">
                <template x-for="(tab, index) in tabs" :key="index">
                    <li class="cursor-pointer py-3 px-6 rounded-t transition"
                        :class="activeTab === index ? 'bg-white border  border-0 text-primary font-semibold' : ' bg-gray-100 text-gray-500'"
                        @click="activeTab = index" x-text="tab"></li>
                </template>
            </ul>
            <div class="w-100 bg-white text-center mx-auto rounded rounded rounded-tl-none">
                <div x-show="activeTab===0">
                    <livewire:users.table />
                </div>
                <div x-show="activeTab===1">
                    <livewire:companies.index />
                </div>
            </div>
        </div>
    </div>
    <!--actual component end-->

    <script>
        @if(session("fromCompany"))
        function setup() {
            return {
                activeTab: 1,
                tabs: [
                    "{{ __('User Management') }}",
                    "{{ __('Company Management') }}",
                ]
            };
        };
        @else
        function setup() {
            return {
                activeTab: 0,
                tabs: [
                    "{{ __('User Management') }}",
                    "{{ __('Company Management') }}",
                ]
            };
        };
        @endif;

    </script>
</section>