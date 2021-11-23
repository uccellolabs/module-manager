<div x-data="{selection: @entangle('selection').defer, deleting: false, isDetailing: @entangle('isDetailing').defer, isEditing: @entangle('isEditing').defer, isCreating: @entangle('isCreating').defer}">
    <div class="flex justify-between">
        <h1 class="text-2xl font-bold uppercase">
            {{ $module->label }}
        </h1>

        <a wire:click="showCreateView" class="flex px-3 py-1 space-x-2 text-white align-middle bg-green-500 border rounded-md cursor-pointer hover:text-green-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="self-center w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Nouveau</span>
        </a>
    </div>

    <div class="my-6">
        <div class="mb-3 bg-white border border-gray-200 rounded-lg">
            <div class="flex p-2 border-b">
                {{-- Delete --}}
                <div class="flex items-center justify-center px-2 py-1 text-sm border border-red-500 rounded-md shadow-sm cursor-pointer bottom-1" x-bind:class="deleting ? 'bg-red-500 text-white' : 'bg-white text-red-500'" x-show="selection.length > 0" x-on:click="deleting ? $wire.deleteSelectedRecords() : null; deleting = !deleting" x-on:click.away="deleting=false">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span class="ml-2" x-show="!deleting">Supprimer</span>
                    <span class="ml-2" x-show="deleting">Supprimer ?</span>
                </div>

                <div class="flex-grow"></div>

                @include('module-manager::livewire.list.field-selector')
            </div>
            <div class="px-5 py-2 overflow-x-scroll scrollbar-thin hover:scrollbar-thumb-gray-200 scrollbar-thumb-gray-300 scrollbar-thumb-rounded-full scrollbar-track-rounded-full" style="height: calc(96vh - 270px)">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="text-sm font-semibold tracking-wide text-left text-gray-900 bg-white">
                            <th></th>
                            @foreach ($filter->fieldsDisplayedInListView($displayedFieldNames) as $field)
                                <x-uc-table-th :module="$module" :field="$field" :sortFieldName="$sortField" :sortOrder="$sortOrder" label="{{ $field->name }}"/>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="overflow-y-scroll bg-white scrollbar-thin hover:scrollbar-thumb-gray-200 scrollbar-thumb-gray-300 scrollbar-thumb-rounded-full scrollbar-track-rounded-full">
                    @forelse ($records as $i => $record)
                        <tr class="text-xs text-gray-700 bg-white border-t md:text-sm transition duration-200 ease-in-out hover:bg-gray-100 cursor-pointer @if($i % 2 === 0)bg-gray-50 @endif" wire:click="showDetailView('{{ $record->getKey() }}')">
                            <td class="px-2 text-center">
                                <input type="checkbox" x-model="selection" value="{{ $record->getKey() }}" x-on:click="$event.stopPropagation(); deleting=false">
                            </td>
                            @foreach ($filter->fieldsDisplayedInListView($displayedFieldNames) as $field)
                                <x-uc-table-td :module="$module" :field="$field" :record="$record"/>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="space-y-4 text-center py-60">
                                <span class="font-semibold text-gray-400">Aucun enregistrement</span>

                                <div class="flex justify-center">
                                    <a wire:click="showCreateView" class="flex px-3 py-1 space-x-2 text-white align-middle bg-green-500 border rounded-md cursor-pointer hover:text-green-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="self-center w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span>Nouveau</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $records->links() }}
        </div>
    </div>

    @if ($currentRecord)
        @include('module-manager::livewire.detail.modal')
        @include('module-manager::livewire.edit.modal')
    @endif
</div>
