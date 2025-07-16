@extends('layout')

@section('title', 'File Uploaded Successfully')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                <i class="fas fa-check text-green-600 text-xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">File Uploaded Successfully!</h1>
            <p class="text-gray-600">Your file is ready to share</p>
        </div>

        <!-- File Info -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">File Details</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">File Name:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $file->original_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">File Size:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $file->formatSize() }}</span>
                </div>
                @if($file->hasPassword())
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Password Protected:</span>
                    <span class="text-sm font-medium text-green-600">
                        <i class="fas fa-lock mr-1"></i>Yes
                    </span>
                </div>
                @endif
                @if($file->expires_at)
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Expires:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $file->expires_at->format('M j, Y g:i A') }}</span>
                </div>
                @endif
                @if($file->max_downloads)
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Download Limit:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $file->max_downloads }} downloads</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Download Link -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Share this link:
            </label>
            <div class="flex">
                <input type="text"
                       id="download-url"
                       value="{{ $downloadUrl }}"
                       readonly
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                <button type="button"
                        id="copy-button"
                        onclick="copyToClipboard()"
                        class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <span id="copy-text">
                        <i class="fas fa-copy mr-1"></i>Copy
                    </span>
                    <span id="copied-text" style="display: none;" class="text-green-200">
                        <i class="fas fa-check mr-1"></i>Copied!
                    </span>
                </button>
            </div>
        </div>

        <!-- QR Code (Optional) -->
        <div class="text-center mb-6">
            <div class="inline-block p-4 bg-white border-2 border-gray-200 rounded-lg">
                <div class="w-32 h-32 bg-gray-100 flex items-center justify-center rounded">
                    <span class="text-xs text-gray-500">QR Code<br>(Optional)</span>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Scan to download on mobile</p>
        </div>

        <!-- Actions -->
        <div class="flex space-x-4">
            <a href="{{ route('home') }}"
               class="flex-1 flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus mr-2"></i>
                Upload Another File
            </a>
            <a href="{{ $downloadUrl }}"
               target="_blank"
               class="flex-1 flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-download mr-2"></i>
                Test Download
            </a>
        </div>
    </div>
</div>

<script>
function copyToClipboard() {
    const urlInput = document.getElementById('download-url');
    const copyText = document.getElementById('copy-text');
    const copiedText = document.getElementById('copied-text');

    try {
        // Select and copy the text
        urlInput.select();
        urlInput.setSelectionRange(0, 99999); // For mobile devices

        // Try modern clipboard API first
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(urlInput.value).then(() => {
                showCopiedState();
            }).catch(() => {
                // Fallback to execCommand
                fallbackCopy();
            });
        } else {
            // Fallback for older browsers
            fallbackCopy();
        }
    } catch (err) {
        console.error('Failed to copy: ', err);
        // Show some user feedback even if copy failed
        alert('Copy failed. Please manually select and copy the URL.');
    }
}

function fallbackCopy() {
    const urlInput = document.getElementById('download-url');
    try {
        document.execCommand('copy');
        showCopiedState();
    } catch (err) {
        console.error('Fallback copy failed: ', err);
        alert('Copy failed. Please manually select and copy the URL.');
    }
}

function showCopiedState() {
    const copyText = document.getElementById('copy-text');
    const copiedText = document.getElementById('copied-text');

    // Show "Copied!" state
    copyText.style.display = 'none';
    copiedText.style.display = 'inline';

    // Reset after 2 seconds
    setTimeout(() => {
        copyText.style.display = 'inline';
        copiedText.style.display = 'none';
    }, 2000);
}

// Auto-select URL on click for easy manual copying
document.addEventListener('DOMContentLoaded', function() {
    const urlInput = document.getElementById('download-url');
    if (urlInput) {
        urlInput.addEventListener('click', function() {
            this.select();
            this.setSelectionRange(0, 99999);
        });
    }
});
</script>
@endsection
