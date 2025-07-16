@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'p-4 bg-green-50 border border-green-200 rounded-lg']) }}>
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">{{ $status }}</p>
            </div>
        </div>
    </div>
@endif
