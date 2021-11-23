<div class="fixed top-0 bottom-0 left-0 right-0 z-40 w-full h-full overflow-y-scroll bg-gray-500 bg-opacity-70" x-show="isDetailing" x-data="{deleting: false}">
    <div class="flex justify-center w-full mt-8">
        {{-- Modal --}}
        <div class="w-full h-full bg-white rounded-lg md:h-auto md:mb-6 md:w-5/6 lg:w-3/6 xl:1/2" x-on:click.away="isDetailing=false; $wire.recordId=null">
            {{-- Title --}}
            <div class="flex justify-end py-4 pl-8 pr-4 space-x-4 border-b border-gray-200">

                <h2 class="self-center text-lg font-semibold text-gray-500">Détail</h2>

                <div class="flex-grow"></div>

                {{-- Edit --}}
                <a wire:click="showEditView('{{ $currentRecord->getKey() }}')" class="flex self-center px-3 py-1 text-blue-500 align-middle bg-blue-100 rounded-md cursor-pointer hover:text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="self-center w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    <span class="ml-2">Éditer</span>
                </a>

                {{-- Delete --}}
                <a class="flex self-center px-3 py-1 align-middle rounded-md cursor-pointer" x-bind:class="deleting ? 'bg-red-500 text-white' : 'bg-red-100 text-red-500 hover:text-red-700'" x-on:click="deleting ? $wire.deleteCurrentRecord() : null; deleting = !deleting" x-on:click.away="deleting=false">
                    <svg xmlns="http://www.w3.org/2000/svg" class="self-center w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span class="ml-2" x-show="!deleting">Supprimer</span>
                    <span class="ml-2" x-show="deleting">Supprimer ?</span>
                </a>

                {{-- Close --}}
                <a x-on:click="isDetailing=false; $wire.recordId=null" class="flex self-center text-gray-500 cursor-pointer hover:text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>

            {{-- Content --}}
            <div class="flex flex-col p-8 space-y-10">
                @foreach ($module->blocks as $block)
                    @continue(!$block->isVisibleInDetailView())
                    <div>
                        <h3 class="text-2xl font-normal text-gray-500">{{ $block->label }}</h3>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                        @foreach ($block->fieldsVisibleInDetailView() as $field)
                            <div @if($field->large ?? false)class="col-span-2"@endif>
                                <div class="text-sm text-gray-400">
                                    {{ $field->label }}
                                </div>

                                <x-uc-detail-field :module="$module" :field="$field" :record="$currentRecord"/>
                            </div>
                        @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        {{-- /Modal --}}
    </div>
    {{-- /Overlay --}}
</div>
