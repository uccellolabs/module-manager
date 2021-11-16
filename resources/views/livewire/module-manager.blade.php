<div x-data="{selection: @entangle('selection').defer, deleting: false, isDetailViewOpen: @entangle('isDetailViewOpen').defer}">
    <div class="flex justify-between mx-6">
        <h1 class="text-2xl font-bold uppercase">
            {{ $module->label }}
        </h1>

        @include('module-manager::livewire.list.field-selector')
    </div>

    <div class="mb-6">
        <div class="px-2 py-4 mb-3 bg-white rounded-lg">
            <div class="px-3 overflow-x-scroll scrollbar-thin hover:scrollbar-thumb-gray-200 scrollbar-thumb-gray-300 scrollbar-thumb-rounded-full scrollbar-track-rounded-full" style="height: calc(96vh - 270px)">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="text-sm font-semibold tracking-wide text-left text-gray-900 bg-white">
                            <th>
                                <div class="flex items-center justify-center px-1 py-2 mt-5 border border-red-500 rounded-lg shadow-sm cursor-pointer bottom-1" x-bind:class="deleting ? 'bg-red-500 text-white' : 'bg-white text-red-500'" x-show="selection.length > 0" x-on:click="deleting ? $wire.deleteSelectedRecords() : null; deleting = !deleting" x-on:click.away="deleting=false">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </div>
                            </th>
                            @foreach ($filter->fieldsDisplayedInListView($displayedFieldNames) as $field)
                                <x-uc-table-th :module="$module" :field="$field" :sortFieldName="$sortField" :sortOrder="$sortOrder" label="{{ $field->name }}"/>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="overflow-y-scroll bg-white scrollbar-thin hover:scrollbar-thumb-gray-200 scrollbar-thumb-gray-300 scrollbar-thumb-rounded-full scrollbar-track-rounded-full">
                    @foreach ($records as $i => $record)
                        <tr class="text-xs text-gray-700 bg-white border-t-2 md:text-sm transition duration-200 ease-in-out hover:bg-gray-200 cursor-pointer @if($i % 2 === 0)bg-gray-100 @endif" wire:click="showDetailView('{{ $record->getKey() }}')">
                            <td class="p-2 text-center">
                                <input type="checkbox" x-model="selection" value="{{ $record->getKey() }}" x-on:click="$event.stopPropagation()">
                            </td>
                            @foreach ($filter->fieldsDisplayedInListView($displayedFieldNames) as $field)
                                <x-uc-table-td :module="$module" :field="$field" :record="$record"/>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $records->links() }}
        </div>
    </div>

    @if ($currentRecord)
        @include('module-manager::livewire.detail.modal')
    @endif
</div>
