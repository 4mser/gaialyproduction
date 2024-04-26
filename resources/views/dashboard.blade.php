<x-app-layout>
    <div class="inline-block w-full min-w-full align-middle md:p-2">
        <div class="w-full overflow-hidden bg-white p-4 md:rounded-md md:shadow-md">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-6 col-start-4">
                    <form id="formDashboard">
                        <select class="block w-full rounded-md border-gray-300 text-xl shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="operation_id">
                            <option value="">{{ __('All inspections') }}</option>
                            @foreach ($operations as $operation)
                                <option value="{{ $operation->id }}" @if (request('operation_id') == $operation->id) selected @endif>
                                    {{ $operation->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="grid-cols-{{ auth()->user()->isUserProfile()? '2': '4' }} col-span-12 col-start-1 my-6 grid gap-4">
                    <div class="flex items-center rounded bg-gray-100 p-4">
                        <div class="bg-primary flex h-14 w-14 items-center justify-center rounded p-2 text-white">
                            <i class="fas fa-list fa-2x"></i>
                        </div>
                        <div class="ml-4 flex flex-col justify-between">
                            <div>{{ __('Inspections') }}</div>
                            <div><strong>{{ $totalInspections }}</strong></div>
                        </div>
                    </div>
                    <div class="flex items-center rounded bg-gray-100 p-4">
                        <div class="bg-primary flex h-14 w-14 items-center justify-center rounded p-2 text-white">
                            <i class="fas fa-object-ungroup fa-2x"></i>
                        </div>
                        <div class="ml-4 flex flex-col justify-between">
                            <div>{{ __('Layers') }}</div>
                            <div><strong>{{ $totalLayers }}</strong></div>
                        </div>
                    </div>
                    @if (!auth()->user()->isUserProfile())
                        <div class="flex items-center rounded bg-gray-100 p-4">
                            <div class="bg-primary flex h-14 w-14 items-center justify-center rounded p-2 text-white">
                                <i class="fas fa-user fa-2x"></i>
                            </div>
                            <div class="ml-4 flex flex-col justify-between">
                                <div>{{ __('Users') }}</div>
                                <div><strong>{{ $totalUsers }}</strong></div>
                            </div>
                        </div>
                        <div class="flex items-center rounded bg-gray-100 p-4">
                            <div class="bg-primary flex h-14 w-14 items-center justify-center rounded p-2 text-white">
                                <i class="fas fa-home fa-2x"></i>
                            </div>
                            <div class="ml-4 flex flex-col justify-between">
                                <div>{{ __('Companies') }}</div>
                                <div><strong>{{ $totalCompanies }}</strong></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <canvas id="bar-chart" class="mt-4" width="800" height="300"></canvas>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.2/chart.min.js" integrity="sha512-tMabqarPtykgDtdtSqCL3uLVM0gS1ZkUAVhRFu1vSEFgvB73niFQWJuvviDyBGBH22Lcau4rHB5p2K2T0Xvr6Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                this.querySelector("select[name=operation_id]").addEventListener('change', function() {
                    document.querySelector('#formDashboard').submit();
                }, false);
            }, false);

            new Chart(document.querySelector("#bar-chart"), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($layers['labels']) !!},
                    datasets: [{
                        label: '{{ __('Layer types') }}',
                        backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#e8c3b9", "#c45850"],
                        data: {{ json_encode($layers['data']) }}
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Predicted world population (millions) in 2050'
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
