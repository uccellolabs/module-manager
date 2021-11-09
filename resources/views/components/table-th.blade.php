<th class="sticky bg-white top-0 p-2 text-left align-top" x-data="{over:false}">
    <div class="flex justify-between cursor-pointer" @if ($sortable === true)wire:click="changeSortOrder('{{ $field }}')"@endif x-on:mouseover="over=true" x-on:mouseout="over=false">
        <span :class="{'text-accent-500': over}" class="whitespace-nowrap">{{ $label }}</span>

        @if ($sortField === $field)
        <div class="flex flex-col items-center justify-center">
            @if ($sortOrder === 'asc')
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
            @elseif($sortOrder === 'desc')
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
            @endif
        </div>
        @endif
    </div>

    @if (!empty(trim($slot)))
    <div>
        <div class="relative flex flex-row items-center mt-2">
            <div class="absolute left-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            {{  $slot }}
        </div>
    </div>
    @endif
</th>
