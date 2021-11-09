<div>
    <div class="flex justify-between mx-6">
        <h1 class="text-2xl font-bold uppercase">
            {{ app('module')->trans($module->name(), $module) }}
        </h1>
        <div class="relative text-sm" x-data="{dropdown:false}">
            <div class="flex items-center p-2 ml-4 bg-blue-500 rounded-lg shadow-sm cursor-pointer" x-on:click="dropdown=!dropdown">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
            </div>
            {{-- Dropdown --}}
            <div class="absolute right-0 z-20 bg-white border border-gray-200 rounded-md shadow-sm w-44 top-12" x-show="dropdown" @click.away="dropdown = false">
                <div class="flex flex-col p-3">
                    @foreach ($module->fields() as $field)
                        {{-- @continue(!$field->isVisibleInListView()) --}}
                        @if (in_array($field->name, $visibleFields))
                            <div class="flex flex-row items-center justify-between px-2 py-1 mb-2 bg-gray-100 rounded-lg cursor-pointer" wire:click="toggleFieldVisibility('{{ $field->name }}')">
                                <div>{{ app('module')->trans('field.'.$field->name, $module) }}</div>
                                <div class="text-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </div>
                            </div>
                        @else
                            <div class="flex flex-row justify-between px-2 py-1 mb-2 cursor-pointer" wire:click="toggleFieldVisibility('{{ $field->name }}')">
                                <div>{{ app('module')->trans('field.'.$field->name, $module) }}</div>
                                <div class="text-blue-500 text-opacity-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    {{-- <div class="flex flex-row justify-between px-2 py-1 mb-2 cursor-pointer">
                        <div class="">Mise à jour</div>
                        <div class="text-blue-900 text-opacity-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <div class="px-2 py-4 mb-3 bg-white rounded-lg">
            <div class="px-3 overflow-x-scroll scrollbar-thin hover:scrollbar-thumb-gray-200 scrollbar-thumb-gray-300 scrollbar-thumb-rounded-full scrollbar-track-rounded-full" style="height: calc(96vh - 270px)">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="text-sm font-semibold tracking-wide text-left text-gray-900 bg-white">
                            @foreach ($filter->fields() as $field)
                                @continue(!$field->isVisibleInListView() || !in_array($field->name, $visibleFields))
                                <x-uc-table-th :module="$module" :field="$field" :sortFieldName="$sortField" :sortOrder="$sortOrder" label="{{ $field->name }}"/>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="overflow-y-scroll bg-white scrollbar-thin hover:scrollbar-thumb-gray-200 scrollbar-thumb-gray-300 scrollbar-thumb-rounded-full scrollbar-track-rounded-full">
                    @foreach ($records as $i => $record)
                        <tr class="text-xs text-gray-700 bg-white border-t-2 md:text-sm transition duration-200 ease-in-out hover:bg-gray-200 @if($i % 2 === 0)bg-gray-100 @endif">
                            @foreach ($filter->fields() as $field)
                                @continue(!$field->isVisibleInListView() || !in_array($field->name, $visibleFields))
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
</div>
