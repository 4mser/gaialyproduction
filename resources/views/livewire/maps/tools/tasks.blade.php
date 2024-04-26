<section>
    <button wire:click="setToggleModal()" class="flex w-16 w-full cursor-pointer flex-col items-center justify-center px-1 py-3 text-center hover:bg-gray-200">
        <img src="img/tasks.svg" class="h-6 w-6" />
        <div class="mt-1 text-xs">{{ __('Tasks') }}</div>
    </button>
    <div class="{{ $toggleModal }} relative z-10">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="items-top flex justify-center p-4 sm:p-0">
                <div class="relative w-full transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-9/12">
                    <div class="bg-white">
                        <h3 class="modal-header p-3 text-center text-lg font-medium leading-6 text-gray-900">
                            {{ __('Tasks') }}
                        </h3>
                        <div class="modal-body">
                            <div class="px-3 text-sm">{{ __('Tasks in the last ' . $this->days . ' days') }}</div>
                            <div class="grid grid-cols-1 px-3">
                                <div class="mb-2">
                                    <input wire:model="search" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:max-w-sm" type="text" name="search" placeholder="{{ __('Search...') }}">
                                </div>
                            </div>
                            @if (count($tasks))
                                <div class="overflow-x-auto px-3" style="max-height: calc(100vh - 300px);">
                                    <table class="pagination-table flex-no-wrap mt-2 flex w-full flex-row divide-y divide-gray-200 overflow-hidden">
                                        <thead class="hidden flex-1 divide-y divide-gray-200 bg-white sm:table-header-group sm:flex-none">
                                            <tr class="flex-no wrap flex flex-col text-sm text-gray-900 sm:table-row">
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Description') }}</th>
                                                <th>{{ __('Actions') }}</th>
                                                <th class="text-right">{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="flex-1 divide-y divide-gray-200 bg-white sm:flex-none">
                                            @foreach ($tasks as $task)
                                                <tr class="flex-no wrap flex flex-col text-sm text-gray-900 sm:table-row">
                                                    <td class="">
                                                        {{ $task->created_at }}
                                                    </td>
                                                    <td class="">
                                                        {{ $task->name }}
                                                        <div class="text-gray-400">{{ $task->uuid }}</div>
                                                    </td>
                                                    <td>
                                                        @if ($task->status == 'completed')
                                                            @if ($task->type == \App\MOdels\Task::TYPE_TILES)
                                                                -
                                                            @elseif ($task->type == \App\MOdels\Task::TYPE_WEBODM)
                                                                <button class=""> {{ _('Download') }}</button>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td class="sm:text-right">
                                                        {{ ucfirst($task->status) }} @if($task->status == 'running') ({{$task->percent_complete}}%) @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="py-6 text-center text-sm text-gray-500">
                                    <i><strong>{{ __('No records') }}</strong></i>
                                </div>
                            @endif
                            {{-- <div style="height: calc(100vh - 160px);">
                                <div class="grid grid-cols-1">
                                    <div class="mb-4">
                                        <input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:max-w-sm" wire:model="name" type="text" name="name" placeholder="{{ _('Search...') }}">
                                    </div>
                                </div>
                                @if (count($tasks))
                                    <div class="max-h-screen overflow-auto">
                                        <table class="pagination-table flex-no-wrap mt-2 flex w-full flex-row divide-y divide-gray-200 overflow-hidden">
                                            <thead class="hidden flex-1 divide-y divide-gray-200 bg-white sm:table-header-group sm:flex-none">
                                                <tr class="flex-no wrap flex flex-col text-sm text-gray-900 sm:table-row">
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Description') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody class="flex-1 divide-y divide-gray-200 bg-white sm:flex-none">
                                                @foreach ($tasks as $task)
                                                    <tr class="flex-no wrap flex flex-col pr-4 text-sm text-gray-900 sm:table-row sm:pr-0">
                                                        <td class="">
                                                            {{ $task->created_at }}
                                                        </td>
                                                        <td class="">
                                                            {{ $task->name }}
                                                            <div class="text-gray-400">{{ $task->uuid }}</div>
                                                            @if ($task->status == 'completed')
                                                                <button class=""> {{ _('Download') }}</button>
                                                            @endif
                                                        </td>
                                                        <td class="sm:pr-4 sm:text-right">
                                                            {{ ucfirst($task->status) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="py-6 text-center text-sm text-gray-500">
                                        <i><strong>{{ __('No records') }}</strong></i>
                                    </div>
                                @endif
                            </div> --}}
                        </div>

                        <div class="modal-footer bg-gray-50 p-3 sm:flex sm:flex-row-reverse">
                            <button wire:click="setToggleModal()" type="button" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium uppercase text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:ml-3 sm:mt-0 sm:w-auto sm:text-sm">
                                {{ __('Close') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="absolute" style="z-index:11000;" wire:loading>
        <livewire:components.loading>
    </div>
</section>
