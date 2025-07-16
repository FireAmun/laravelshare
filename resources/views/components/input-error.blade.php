@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-center space-x-1">
                <i class="fas fa-exclamation-circle text-red-500 text-xs"></i>
                <span>{{ $message }}</span>
            </li>
        @endforeach
    </ul>
@endif
