@extends('admin.layout')

@section('content')
<div class="py-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Page header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">System Settings</h1>
            <p class="mt-1 text-sm text-gray-600">Configure system parameters and limits</p>
        </div>

        <!-- Settings Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- File Upload Settings -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">File Upload Settings</h3>

                <dl class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Maximum File Size</dt>
                        <dd class="text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">{{ $settings['max_file_size'] ?? '5MB' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-2">Allowed File Extensions</dt>
                        <dd class="text-sm text-gray-900">
                            @if(isset($settings['allowed_extensions']) && is_array($settings['allowed_extensions']))
                                <div class="flex flex-wrap gap-2">
                                    @foreach($settings['allowed_extensions'] as $extension)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            .{{ $extension }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-500 italic">Not configured</span>
                            @endif
                        </dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Max Downloads Per File</dt>
                        <dd class="text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">{{ $settings['max_downloads'] ?? 100 }}</dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Default Expiry (Days)</dt>
                        <dd class="text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">{{ $settings['default_expiry_days'] ?? 7 }}</dd>
                    </div>
                </dl>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-600">
                        <strong>Note:</strong> These settings are configured in your Laravel configuration files.
                        To modify them, update the corresponding values in your <code class="bg-gray-100 px-1 rounded">config/</code> directory files.
                    </p>
                </div>
            </div>

            <!-- System Information -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">System Information</h3>

                <dl class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Laravel Version</dt>
                        <dd class="text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">{{ app()->version() }}</dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">PHP Version</dt>
                        <dd class="text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">{{ PHP_VERSION }}</dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Environment</dt>
                        <dd class="text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ app()->environment('production') ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ strtoupper(app()->environment()) }}
                            </span>
                        </dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Debug Mode</dt>
                        <dd class="text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ config('app.debug') ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                {{ config('app.debug') ? 'ENABLED' : 'DISABLED' }}
                            </span>
                        </dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Application URL</dt>
                        <dd class="text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded break-all">{{ config('app.url') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Security Settings -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Security Settings</h3>

                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-2">Password Protection</dt>
                        <dd class="text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                ENABLED
                            </span>
                            <p class="text-xs text-gray-500 mt-1">Users can protect files with passwords</p>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-2">File Expiry</dt>
                        <dd class="text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                ENABLED
                            </span>
                            <p class="text-xs text-gray-500 mt-1">Files automatically expire after the specified time</p>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-2">Download Limits</dt>
                        <dd class="text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                ENABLED
                            </span>
                            <p class="text-xs text-gray-500 mt-1">Users can set maximum download limits</p>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-2">Anonymous Uploads</dt>
                        <dd class="text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                ENABLED
                            </span>
                            <p class="text-xs text-gray-500 mt-1">Non-registered users can upload files</p>
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Storage Information -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Storage Information</h3>

                <dl class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Storage Driver</dt>
                        <dd class="text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">{{ config('filesystems.default') }}</dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Public Storage Path</dt>
                        <dd class="text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded text-xs break-all">{{ storage_path('app/public') }}</dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Upload Temp Directory</dt>
                        <dd class="text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded text-xs break-all">{{ sys_get_temp_dir() }}</dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Max Upload Size (PHP)</dt>
                        <dd class="text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">{{ ini_get('upload_max_filesize') }}</dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Max Post Size (PHP)</dt>
                        <dd class="text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">{{ ini_get('post_max_size') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Configuration Tips -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-medium text-blue-900 mb-4">
                <svg class="inline h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
                Configuration Tips
            </h3>

            <div class="space-y-3 text-sm text-blue-800">
                <p><strong>File Upload Limits:</strong> Modify <code class="bg-blue-100 px-1 rounded">config/filesystems.php</code> to change file size limits and allowed extensions.</p>

                <p><strong>Default Settings:</strong> Update <code class="bg-blue-100 px-1 rounded">config/app.php</code> to change default download limits and expiry days.</p>

                <p><strong>Security:</strong> For production environments, ensure debug mode is disabled and use HTTPS for secure file transfers.</p>

                <p><strong>Storage:</strong> Consider using cloud storage (S3, etc.) for better scalability in production environments.</p>

                <p><strong>Performance:</strong> Implement caching strategies and consider using a CDN for frequently accessed files.</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>

            <div class="flex flex-wrap gap-4">
                <form method="POST" action="{{ route('admin.cleanup-expired-files') }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                        Cleanup Expired Files
                    </button>
                </form>

                <a href="{{ route('admin.files') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    Manage Files
                </a>

                <a href="{{ route('admin.users') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    Manage Users
                </a>

                <a href="{{ route('admin.chart-test') }}" class="inline-flex items-center px-4 py-2 border border-orange-300 text-sm font-medium rounded-md text-orange-700 bg-orange-50 hover:bg-orange-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.611L5 14.5" />
                    </svg>
                    Chart.js Diagnostics
                </a>

                <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Back to Site
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
