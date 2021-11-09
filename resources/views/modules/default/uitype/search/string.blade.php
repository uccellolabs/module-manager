
<div class="relative flex flex-row items-center mt-2">
    <div class="absolute left-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </div>
    <input type="text" class="w-full h-8 pl-10 bg-white border border-gray-200 rounded-lg shadow-sm" autocomplete="off" wire:model.debounce.500ms="search.{{ $field->name }}">
</div>
