@extends('layout')

@section('title', 'Password Required')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                <i class="fas fa-lock text-yellow-600 text-xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Password Required</h1>
            <p class="text-gray-600">This file is password protected</p>
        </div>

        <!-- File Info -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-file text-blue-600 text-lg mr-3"></i>
                <div>
                    <div class="text-sm font-medium text-gray-900">{{ $file->original_name }}</div>
                    <div class="text-xs text-gray-500">{{ $file->formatSize() }}</div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('file.download.post', $file->uuid) }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Enter Password
                </label>
                <div class="relative">
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter the file password">
                    <button type="button"
                            onclick="togglePasswordVisibility()"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i id="password-toggle-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                    </button>
                </div>
            </div>

            <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-download mr-2"></i>
                Download File
            </button>
        </form>

        <!-- File Stats -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex justify-between text-xs text-gray-500">
                <span>Downloads: {{ $file->downloads }}{{ $file->max_downloads ? ' / ' . $file->max_downloads : '' }}</span>
                @if($file->expires_at)
                    <span>Expires: {{ $file->expires_at->format('M j, Y') }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('password-toggle-icon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.className = 'fas fa-eye-slash text-gray-400 hover:text-gray-600';
    } else {
        passwordInput.type = 'password';
        toggleIcon.className = 'fas fa-eye text-gray-400 hover:text-gray-600';
    }
}
</script>
@endsection
