@props(['disabled' => false])

<input
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => '
            block w-full text-lg py-3 px-4 rounded-lg
            bg-gray-900 text-white
            focus:border-royal focus:ring focus:ring-royal focus:ring-opacity-50
            disabled:opacity-50 disabled:cursor-not-allowed
        '
    ]) !!}
>
