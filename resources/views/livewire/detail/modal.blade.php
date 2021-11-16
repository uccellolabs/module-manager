<div class="fixed top-0 bottom-0 left-0 right-0 z-40 w-full h-full overflow-y-scroll bg-gray-500 bg-opacity-70" x-show="isDetailViewOpen">
    <div class="flex justify-center w-full mt-8">
        {{-- Modal --}}
        <div class="w-full h-full p-10 bg-white rounded-lg md:h-auto md:mb-6 md:px-10 md:py-12 md:w-5/6 lg:w-3/6 xl:1/2" x-on:click.away="isDetailViewOpen=false">
            {{-- Title --}}
            <div class="flex flex-row justify-between">
                <h1 class="text-sm font-semibold md:text-2xl">{{ $currentRecord->name }}</h1>
                <a x-on:click="isDetailViewOpen=false" class="text-gray-500 cursor-pointer hover:text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>

            {{-- Content --}}
            <div class="flex flex-col mt-8 space-y-8">
                @foreach ($module->blocks as $block)
                    <div class="px-8 py-4 border border-gray-200 rounded-md bg-gray-50">
                        <h2 class="font-semibold text-md md:text-xl">{{ $block->label }}</h2>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                        @foreach ($block->fieldsVisibleInDetailView() as $field)
                            <div>
                                <div class="font-semibold">
                                    {{ $field->label }}
                                </div>

                                <x-uc-detail-value :module="$module" :field="$field" :record="$currentRecord"/>
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
