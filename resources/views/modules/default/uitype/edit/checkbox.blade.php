<div class="flex flex-row py-4 cursor-pointer" x-data="{value:false}">
    <div class="flex items-center" x-on:click="value=!value">
        <div class="relative flex items-center w-10 h-5 transition duration-200 ease-linear border-2 rounded-full" x-bind:class="value ? 'border-green-500' : 'border-gray-400'">
            <div class="absolute w-3 h-3 transition duration-100 ease-linear transform rounded-full cursor-pointer left-1" x-bind:class="value ? 'translate-x-4 bg-green-500' : 'translate-x-0 bg-gray-400'"></div>
        </div>
    </div>
    <div class="ml-2" x-text="value ? 'Oui' : 'Non'"></div>
</div>
