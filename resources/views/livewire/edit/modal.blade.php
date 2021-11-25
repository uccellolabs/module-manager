<div class="fixed top-0 bottom-0 left-0 right-0 z-40 w-full h-full overflow-y-scroll bg-gray-500 bg-opacity-70" x-show="isEditing || isCreating">
    <form wire:submit.prevent="save" autocomplete="off" autocapitalize="off" novalidate>
        <div class="flex justify-center w-full mt-8">
            {{-- Modal --}}
            {{-- <div class="w-full h-full bg-white rounded-lg md:h-auto md:mb-6 md:w-5/6 lg:w-3/6 xl:1/2" x-on:click.away="isEditing=false; isCreating=false; $wire.recordId=null"> --}}
            <div class="w-full h-full bg-white rounded-lg md:h-auto md:mb-6 md:w-5/6 lg:w-3/6 xl:1/2">
                {{-- Title --}}
                <div class="flex justify-end py-4 pl-8 pr-4 space-x-4 border-b border-gray-200">

                    <h2 class="self-center text-lg font-semibold text-gray-500">Édition</h2>

                    <div class="flex-grow"></div>

                    {{-- Edit --}}
                    <button type="submit" wire:click="save" class="flex self-center px-3 py-1 text-green-500 align-middle bg-green-100 rounded-md cursor-pointer hover:text-green-700">
                        <span class="mr-2">Sauvegarder</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="self-center w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </button>

                    {{-- Close --}}
                    <a x-on:click="isEditing=false; isCreating=false; $wire.recordId=null" class="flex self-center text-gray-500 cursor-pointer hover:text-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </div>

                {{-- Content --}}
                <div class="flex flex-col p-8 space-y-10">
                    @foreach ($module->blocks as $block)
                        @continue(($isEditing && !$block->isVisibleInEditView()) || ($isCreating && !$block->isVisibleInCreateView()))
                        <div>
                            <h3 class="text-2xl font-normal">{{ $block->label }}</h3>

                            <div class="grid grid-cols-2 gap-4 mt-4">
                            @foreach ($block->fields as $field)
                                @continue(($isEditing && !$field->isVisibleInEditView()) || ($isCreating && !$field->isVisibleInCreateView()))
                                <div @if($field->large ?? false)class="col-span-2"@endif>
                                    <div class="text-sm text-gray-400">
                                        {{ $field->label }}
                                    </div>

                                    {{-- Field --}}
                                    <x-uc-edit-field :module="$module" :field="$field" :record="$currentRecord"/>

                                    {{-- Error --}}
                                    @error('currentRecord.'.$field->column)<span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                            @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            {{-- /Modal --}}
        </div>
    </form>
    {{-- /Overlay --}}
</div>
