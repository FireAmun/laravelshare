@extends('layout')

@section('title', 'Upload File')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
    <!-- Hero Section -->
    <div class="text-center mb-12">
        <div class="mx-auto h-16 w-16 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center mb-6">
            <i class="fas fa-cloud-upload-alt text-2xl text-white"></i>
        </div>
        <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl mb-4">
            Share Files Securely
        </h1>
        <p class="mx-auto max-w-2xl text-lg text-gray-600">
            Upload your files and generate secure, shareable links with optional password protection and expiration dates.
        </p>
        @guest
            <div class="mt-6 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                    <i class="fas fa-user-plus mr-2"></i>
                    Create Free Account
                </a>
                <a href="{{ route('login') }}" class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Sign In
                </a>
            </div>
        @endguest
    </div>

    <!-- Security Notice -->
    <div class="mb-8 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-shield-alt text-green-600 mt-1"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-green-800">Security Features</h3>
                <div class="mt-2 text-xs text-green-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Files are automatically scanned for security threats</li>
                        <li>Optional password protection and expiration dates</li>
                        <li>{{ config('security.files.encrypt_files') ? 'Files are encrypted at rest' : 'Files are stored securely' }}</li>
                        <li>All uploads and downloads are logged for security</li>
                        <li>Rate limiting prevents abuse ({{ config('security.rate_limiting.uploads_per_hour') }} uploads/hour)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Form -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" id="upload-form">
        @auth
            <!-- ...existing auth section... -->
        @endauth

        @if ($errors->any())
            <!-- ...existing errors section... -->
        @endif

        <div class="p-8">
            <form action="{{ route('file.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- File Upload Zone -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-3">
                        <i class="fas fa-file mr-2"></i>
                        Choose File to Upload
                    </label>
                    <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-300 px-6 py-10 hover:border-indigo-400 transition-colors" id="upload-zone">
                        <div class="text-center">
                            <!-- Default Upload Area -->
                            <div id="upload-default">
                                <i class="fas fa-cloud-upload-alt text-5xl text-gray-400 mb-4"></i>
                                <div class="mt-4 flex text-sm leading-6 text-gray-600">
                                    <label for="file-upload" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                                        <span>Upload a file</span>
                                        <input id="file-upload" name="file" type="file" class="sr-only" required onchange="handleFileUpload(this)">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs leading-5 text-gray-600">Files up to 5MB</p>
                                <p class="text-xs leading-5 text-gray-500 mt-1">Documents and images only</p>
                            </div>

                            <!-- File Selected Display -->
                            <div id="file-display" style="display: none;" class="p-6 bg-white rounded-lg border-2 border-indigo-200">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-file text-indigo-600 text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-lg font-semibold text-gray-900" id="file-name">No file selected</h4>
                                        <p class="text-sm text-gray-600" id="file-size">Unknown size</p>

                                        <!-- Success Message -->
                                        <div id="success-message" style="display: none;" class="mt-2">
                                            <div class="flex items-center text-green-600">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                <span class="text-sm font-medium">Ready to upload</span>
                                            </div>
                                        </div>

                                        <!-- Error Message -->
                                        <div id="error-message" style="display: none;" class="mt-2">
                                            <div class="flex items-center text-red-600">
                                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                                <span class="text-sm font-medium" id="error-text"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <button type="button" onclick="clearFile()" class="w-8 h-8 bg-red-100 hover:bg-red-200 rounded-full flex items-center justify-center text-red-600 transition-colors">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload Options -->
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                    <!-- Security Options -->
                    <div class="space-y-6">
                        <div class="flex items-center space-x-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white shadow-lg">
                                <i class="fas fa-shield-alt text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Security Options</h3>
                                <p class="text-sm text-gray-500">Protect your files with advanced security</p>
                            </div>
                        </div>

                        <!-- Password Protection -->
                        <div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-gradient-to-br from-white to-gray-50 p-6 shadow-sm hover:shadow-md transition-all duration-200">
                            <div class="absolute inset-0 bg-gradient-to-r from-purple-500/5 to-indigo-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                            <label class="relative flex items-start space-x-4 cursor-pointer">
                                <div class="flex items-center h-6">
                                    <input type="checkbox"
                                           id="use-password"
                                           onchange="togglePassword()"
                                           class="h-5 w-5 rounded-lg border-gray-300 text-purple-600 focus:ring-purple-500 focus:ring-2 focus:ring-offset-2 transition-all duration-200 shadow-sm">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100 text-purple-600 group-hover:bg-purple-200 transition-colors duration-200">
                                                <i class="fas fa-lock text-sm"></i>
                                            </div>
                                            <div>
                                                <span class="text-base font-semibold text-gray-900">Password Protection</span>
                                                <p class="text-sm text-gray-600">Require a password to download</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-shield-alt mr-1"></i>
                                                Secure
                                            </span>
                                        </div>
                                    </div>
                                    <div id="password-section" style="display: none;" class="mt-4">
                                        <div class="relative">
                                            <input type="password"
                                                   id="password-input"
                                                   name="password"
                                                   placeholder="Enter a secure password (min. 4 characters)"
                                                   class="block w-full rounded-xl border-0 py-3 pl-4 pr-12 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-purple-600 sm:text-sm transition-all duration-200 bg-white/80 backdrop-blur-sm">
                                            <button type="button"
                                                    onclick="togglePasswordVisibility()"
                                                    class="absolute inset-y-0 right-0 flex items-center pr-4 hover:text-purple-600 transition-colors duration-200">
                                                <i id="password-toggle-icon" class="fas fa-eye h-4 w-4 text-gray-400"></i>
                                            </button>
                                        </div>
                                        <div class="mt-2 flex items-center space-x-2 text-xs text-gray-500">
                                            <i class="fas fa-info-circle"></i>
                                            <span>Use a strong password with letters, numbers, and symbols</span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Expiration Options -->
                    <div class="space-y-6">
                        <div class="flex items-center space-x-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 text-white shadow-lg">
                                <i class="fas fa-clock text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Expiration Settings</h3>
                                <p class="text-sm text-gray-500">Control when your files expire</p>
                            </div>
                        </div>

                        <!-- Time Expiration -->
                        <div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-gradient-to-br from-white to-gray-50 p-6 shadow-sm hover:shadow-md transition-all duration-200">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 to-cyan-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                            <label class="relative flex items-start space-x-4 cursor-pointer">
                                <div class="flex items-center h-6">
                                    <input type="checkbox"
                                           id="use-expiration"
                                           onchange="toggleExpiration()"
                                           class="h-5 w-5 rounded-lg border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-2 focus:ring-offset-2 transition-all duration-200 shadow-sm">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-600 group-hover:bg-blue-200 transition-colors duration-200">
                                                <i class="fas fa-hourglass-half text-sm"></i>
                                            </div>
                                            <div>
                                                <span class="text-base font-semibold text-gray-900">Auto-delete after</span>
                                                <p class="text-sm text-gray-600">File will be deleted automatically</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-timer mr-1"></i>
                                                Timed
                                            </span>
                                        </div>
                                    </div>
                                    <div id="expiration-section" style="display: none;" class="mt-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-1">
                                                <input type="number"
                                                       name="expires_in_days"
                                                       value="7"
                                                       min="1"
                                                       max="30"
                                                       class="block w-full rounded-xl border-0 py-3 pl-4 pr-4 text-center text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm transition-all duration-200 bg-white/80 backdrop-blur-sm font-semibold">
                                            </div>
                                            <div class="text-gray-600 font-medium">days</div>
                                        </div>
                                        <div class="mt-2 flex items-center space-x-2 text-xs text-gray-500">
                                            <i class="fas fa-info-circle"></i>
                                            <span>Files will be permanently deleted after expiration</span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Download Limit -->
                        <div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-gradient-to-br from-white to-gray-50 p-6 shadow-sm hover:shadow-md transition-all duration-200">
                            <div class="absolute inset-0 bg-gradient-to-r from-green-500/5 to-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                            <label class="relative flex items-start space-x-4 cursor-pointer">
                                <div class="flex items-center h-6">
                                    <input type="checkbox"
                                           id="use-download-limit"
                                           onchange="toggleDownloadLimit()"
                                           class="h-5 w-5 rounded-lg border-gray-300 text-green-600 focus:ring-green-500 focus:ring-2 focus:ring-offset-2 transition-all duration-200 shadow-sm">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-100 text-green-600 group-hover:bg-green-200 transition-colors duration-200">
                                                <i class="fas fa-download text-sm"></i>
                                            </div>
                                            <div>
                                                <span class="text-base font-semibold text-gray-900">Download Limit</span>
                                                <p class="text-sm text-gray-600">Maximum number of downloads</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-hashtag mr-1"></i>
                                                Limited
                                            </span>
                                        </div>
                                    </div>
                                    <div id="download-limit-section" style="display: none;" class="mt-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-1">
                                                <input type="number"
                                                       name="max_downloads"
                                                       value="10"
                                                       min="1"
                                                       max="1000"
                                                       class="block w-full rounded-xl border-0 py-3 pl-4 pr-4 text-center text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm transition-all duration-200 bg-white/80 backdrop-blur-sm font-semibold">
                                            </div>
                                            <div class="text-gray-600 font-medium">downloads</div>
                                        </div>
                                        <div class="mt-2 flex items-center space-x-2 text-xs text-gray-500">
                                            <i class="fas fa-info-circle"></i>
                                            <span>File will be deleted after reaching download limit</span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Upload Button -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-6 border-t border-gray-200 gap-4">
                    <div class="text-xs text-gray-500 flex items-center">
                        <i class="fas fa-info-circle mr-1"></i>
                        Files are stored securely and can be managed from your dashboard
                    </div>
                    <button type="submit"
                            id="upload-button"
                            disabled
                            class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-3 text-sm font-semibold text-white shadow-lg hover:shadow-xl hover:from-indigo-700 hover:to-purple-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none whitespace-nowrap">
                        <i class="fas fa-cloud-upload-alt mr-2"></i>
                        Upload & Generate Link
                    </button>
                </div>
            </form>
        </div>
    </div>

    @guest
    <!-- Features for Non-authenticated Users -->
    <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
        <div class="text-center">
            <div class="mx-auto h-12 w-12 rounded-lg bg-indigo-100 flex items-center justify-center">
                <i class="fas fa-shield-alt text-indigo-600"></i>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-gray-900">Secure Sharing</h3>
            <p class="mt-2 text-sm text-gray-600">Password protection and expiration dates keep your files secure.</p>
        </div>
        <div class="text-center">
            <div class="mx-auto h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center">
                <i class="fas fa-tachometer-alt text-green-600"></i>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-gray-900">File Management</h3>
            <p class="mt-2 text-sm text-gray-600">Track downloads, manage files, and view statistics with a free account.</p>
        </div>
        <div class="text-center sm:col-span-2 lg:col-span-1">
            <div class="mx-auto h-12 w-12 rounded-lg bg-purple-100 flex items-center justify-center">
                <i class="fas fa-magic text-purple-600"></i>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-gray-900">Easy to Use</h3>
            <p class="mt-2 text-sm text-gray-600">Drag and drop files, copy links, and share with anyone instantly.</p>
        </div>
    </div>
    @endguest
</div>

<script>
// Global variables
let selectedFile = null;
let fileIsValid = false;

// File size limit: 5MB
const maxFileSize = 5 * 1024 * 1024;

// Allowed file extensions
const allowedExtensions = [
    'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv', 'rtf',
    'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg',
    'zip', 'rar', '7z', 'gz'
];

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Upload form JavaScript initialized');
    setupDragAndDrop();
    updateUploadButton();
});

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Validate file
function validateFile(file) {
    console.log('Validating file:', file.name, file.size);

    // Check file size
    if (file.size > maxFileSize) {
        showError('File too large. Maximum size is 5 MB');
        return false;
    }

    // Check file type
    const fileName = file.name.toLowerCase();
    const fileExtension = fileName.split('.').pop();

    if (!allowedExtensions.includes(fileExtension)) {
        showError('File type not allowed. Only documents, images, and ZIP files are supported');
        return false;
    }

    showSuccess();
    return true;
}

// Handle file upload
function handleFileUpload(input) {
    console.log('File input changed');
    const file = input.files[0];

    if (file) {
        console.log('File selected:', file.name, file.size);
        selectedFile = file;

        // Show file details
        document.getElementById('file-name').textContent = file.name;
        document.getElementById('file-size').textContent = formatFileSize(file.size);

        // Validate file
        fileIsValid = validateFile(file);

        // Show/hide appropriate sections
        document.getElementById('upload-default').style.display = 'none';
        document.getElementById('file-display').style.display = 'block';

        updateUploadButton();
    } else {
        clearFile();
    }
}

// Clear file selection
function clearFile() {
    console.log('Clearing file selection');
    selectedFile = null;
    fileIsValid = false;

    // Reset file input
    document.getElementById('file-upload').value = '';

    // Reset display
    document.getElementById('upload-default').style.display = 'block';
    document.getElementById('file-display').style.display = 'none';

    // Hide messages
    document.getElementById('success-message').style.display = 'none';
    document.getElementById('error-message').style.display = 'none';

    updateUploadButton();
}

// Show success message
function showSuccess() {
    document.getElementById('success-message').style.display = 'block';
    document.getElementById('error-message').style.display = 'none';
}

// Show error message
function showError(message) {
    document.getElementById('error-text').textContent = message;
    document.getElementById('error-message').style.display = 'block';
    document.getElementById('success-message').style.display = 'none';
}

// Update upload button state
function updateUploadButton() {
    const button = document.getElementById('upload-button');
    if (selectedFile && fileIsValid) {
        button.disabled = false;
    } else {
        button.disabled = true;
    }
}

// Toggle password section
function togglePassword() {
    const checkbox = document.getElementById('use-password');
    const section = document.getElementById('password-section');

    if (checkbox.checked) {
        section.style.display = 'block';
    } else {
        section.style.display = 'none';
    }
}

// Toggle password visibility
function togglePasswordVisibility() {
    const input = document.getElementById('password-input');
    const icon = document.getElementById('password-toggle-icon');

    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash h-4 w-4 text-gray-400';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye h-4 w-4 text-gray-400';
    }
}

// Toggle expiration section
function toggleExpiration() {
    const checkbox = document.getElementById('use-expiration');
    const section = document.getElementById('expiration-section');

    if (checkbox.checked) {
        section.style.display = 'block';
    } else {
        section.style.display = 'none';
    }
}

// Toggle download limit section
function toggleDownloadLimit() {
    const checkbox = document.getElementById('use-download-limit');
    const section = document.getElementById('download-limit-section');

    if (checkbox.checked) {
        section.style.display = 'block';
    } else {
        section.style.display = 'none';
    }
}

// Setup drag and drop
function setupDragAndDrop() {
    const uploadZone = document.getElementById('upload-zone');

    if (!uploadZone) {
        console.log('Upload zone not found');
        return;
    }

    uploadZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadZone.classList.add('border-indigo-400', 'bg-indigo-50');
    });

    uploadZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadZone.classList.remove('border-indigo-400', 'bg-indigo-50');
    });

    uploadZone.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadZone.classList.remove('border-indigo-400', 'bg-indigo-50');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            console.log('File dropped:', file.name);

            // Set the file input
            const fileInput = document.getElementById('file-upload');
            fileInput.files = files;

            // Handle the file
            handleFileUpload(fileInput);
        }
    });
}
</script>
@endsection
