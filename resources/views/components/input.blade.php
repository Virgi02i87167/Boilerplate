@props(['disabled' => false, 'type' => 'text', 'label' => null, 'name' => null])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
            {{ $label }}
        </label>
    @endif
    
    <input 
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}" 
        {{ $disabled ? 'disabled' : '' }} 
        {!! $attributes->merge([
            'class' => 'w-full px-4 py-2 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg text-[#1b1b18] dark:text-white placeholder-[#A1A09A] focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-all disabled:opacity-50 disabled:cursor-not-allowed'
        ]) !!}
    >

    @if($name && $errors->has($name))
        <ul class="text-sm text-red-600 dark:text-red-400 mt-2 space-y-1">
            @foreach ($errors->get($name) as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    @endif
</div>