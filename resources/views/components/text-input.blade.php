@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'text-sm py-2 border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm']) !!}>
