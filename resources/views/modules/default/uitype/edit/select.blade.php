<div x-data="{open:false, value:'', label:''}" x-on:click.away="open=false" class="relative">
    <input type="text"class="w-full px-4 py-2 mt-2 leading-normal border border-gray-100 rounded-md shadow cursor-pointer" x-bind:value="label" readonly x-on:click="open=!open">
    <input type="hidden" x-bind:value="value">

    <div x-show="open" class="absolute z-40 w-full p-4 bg-white border border-gray-200 rounded-b-lg shadow-md -top-30">
        @foreach ($field->options->choices as $choice)
            @php($label = is_object($choice) ? $choice->label : $choice)
            @php($value = is_object($choice) ? $choice->value : $choice)
            <a class="block px-3 py-1 cursor-pointer hover:bg-gray-100" x-on:click="value='{{ $value }}'; label='{{ $label }}'; open=false">{{ $label }}</a>
        @endforeach
    </div>

    <div class="absolute right-2 top-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </div>
</div>
