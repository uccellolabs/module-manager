<th class="sticky top-0 p-2 text-left align-top bg-white" x-data="{over:false}">
    <div class="flex justify-between cursor-pointer" wire:click="changeSortOrder('{{ $fieldName }}')" x-on:mouseover="over=true" x-on:mouseout="over=false">
        <span :class="{'text-blue-500': over}" class="whitespace-nowrap">{{ app('module')->trans('field.'.$fieldName, $module) }}</span>

        @if ($sortFieldName === $fieldName)
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

    {{-- Search --}}
    <x-uc-table-search :module="$module" :field="$field"></x-uc-table-search>
</th>
