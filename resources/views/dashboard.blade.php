@extends('layout')

@section('title', 'Dashboard')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                <p class="mt-2 text-sm text-gray-700">
                    Manage your uploaded files and view statistics.
                </p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('home') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Upload New File
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Total Files -->
        <div class="card-hover overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-gray-500">Total Files</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $stats['total_files'] }}</dd>
            <div class="mt-2 flex items-center text-sm text-gray-600">
                <i class="fas fa-files text-indigo-500 mr-1"></i>
                All uploaded files
            </div>
        </div>

        <!-- Total Downloads -->
        <div class="card-hover overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-gray-500">Total Downloads</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ number_format($stats['total_downloads']) }}</dd>
            <div class="mt-2 flex items-center text-sm text-gray-600">
                <i class="fas fa-download text-green-500 mr-1"></i>
                Files downloaded
            </div>
        </div>

        <!-- Active Files -->
        <div class="card-hover overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-gray-500">Active Files</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $stats['active_files'] }}</dd>
            <div class="mt-2 flex items-center text-sm text-gray-600">
                <i class="fas fa-check-circle text-emerald-500 mr-1"></i>
                Available for download
            </div>
        </div>

        <!-- Storage Used -->
        <div class="card-hover overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-gray-500">Storage Used</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                {{ \App\Models\File::formatStorageSize($stats['storage_used']) }}
            </dd>
            <div class="mt-2 flex items-center text-sm text-gray-600">
                <i class="fas fa-hdd text-purple-500 mr-1"></i>
                Total space used
            </div>
        </div>
    </div>

    <!-- Files Table -->
    <div class="overflow-hidden bg-white shadow sm:rounded-md">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Your Files</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Recent uploads and their download statistics.
            </p>
        </div>

        @if($files->count() > 0)
            <ul role="list" class="divide-y divide-gray-200">
                @foreach($files as $file)
                    <li x-data="{ showActions: false }" @mouseenter="showActions = true" @mouseleave="showActions = false">
                        <div class="px-4 py-4 sm:px-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center min-w-0 flex-1">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                            @php
                                                $extension = pathinfo($file->original_name, PATHINFO_EXTENSION);
                                                $iconClass = match(strtolower($extension)) {
                                                    'pdf' => 'fas fa-file-pdf text-red-300',
                                                    'doc', 'docx' => 'fas fa-file-word text-blue-300',
                                                    'xls', 'xlsx' => 'fas fa-file-excel text-green-300',
                                                    'ppt', 'pptx' => 'fas fa-file-powerpoint text-orange-300',
                                                    'jpg', 'jpeg', 'png', 'gif' => 'fas fa-file-image text-pink-300',
                                                    'mp4', 'avi', 'mov' => 'fas fa-file-video text-purple-300',
                                                    'mp3', 'wav' => 'fas fa-file-audio text-yellow-300',
                                                    'zip', 'rar' => 'fas fa-file-archive text-gray-300',
                                                    default => 'fas fa-file text-gray-300'
                                                };
                                            @endphp
                                            <i class="{{ $iconClass }}"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4 min-w-0 flex-1">
                                        <div class="flex items-center">
                                            <p class="truncate text-sm font-medium text-gray-900">
                                                {{ $file->original_name }}
                                            </p>
                                            @if($file->hasPassword())
                                                <i class="fas fa-lock text-yellow-500 ml-2 text-xs" title="Password Protected"></i>
                                            @endif
                                            @if($file->isExpired())
                                                <span class="ml-2 inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                                    Expired
                                                </span>
                                            @else
                                                <span class="ml-2 inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                                    Active
                                                </span>
                                            @endif
                                        </div>
                                        <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500">
                                            <span class="flex items-center">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $file->created_at->format('M j, Y g:i A') }}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-download mr-1"></i>
                                                {{ $file->downloads }}{{ $file->max_downloads ? '/'.$file->max_downloads : '' }} downloads
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-weight mr-1"></i>
                                                {{ $file->formatSize() }}
                                            </span>
                                            @if($file->expires_at)
                                                <span class="flex items-center">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Expires {{ $file->expires_at->format('M j, Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-4 flex items-center space-x-2" x-show="showActions" x-transition>
                                    <!-- Copy Link Button -->
                                    <button @click="navigator.clipboard.writeText('{{ route('file.download', $file->uuid) }}');
                                                    $refs.toast.classList.remove('hidden');
                                                    setTimeout(() => $refs.toast.classList.add('hidden'), 2000)"
                                            class="inline-flex items-center rounded bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 hover:bg-indigo-100 transition-colors">
                                        <i class="fas fa-copy mr-1"></i>
                                        Copy Link
                                    </button>

                                    <!-- View Button -->
                                    <a href="{{ route('file.download', $file->uuid) }}" target="_blank"
                                       class="inline-flex items-center rounded bg-green-50 px-2 py-1 text-xs font-medium text-green-700 hover:bg-green-100 transition-colors">
                                        <i class="fas fa-external-link-alt mr-1"></i>
                                        View
                                    </a>

                                    <!-- Delete Button -->
                                    <form method="POST" action="{{ route('file.delete', $file->uuid) }}" class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this file? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center rounded bg-red-50 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-100 transition-colors">
                                            <i class="fas fa-trash mr-1"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <!-- Pagination -->
            @if($files->hasPages())
                <div class="bg-white px-4 py-3 sm:px-6">
                    {{ $files->links() }}
                </div>
            @endif
        @else
            <div class="px-4 py-12 text-center sm:px-6">
                <div class="mx-auto h-12 w-12 text-gray-400">
                    <i class="fas fa-file-upload text-4xl"></i>
                </div>
                <h3 class="mt-4 text-sm font-medium text-gray-900">No files uploaded</h3>
                <p class="mt-2 text-sm text-gray-500">
                    Get started by uploading your first file.
                </p>
                <div class="mt-6">
                    <a href="{{ route('home') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        <i class="fas fa-plus mr-2"></i>
                        Upload File
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Toast notification for copy action -->
    <div x-ref="toast" class="fixed bottom-4 right-4 hidden">
        <div class="rounded-md bg-green-50 p-4 shadow-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        Link copied to clipboard!
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
