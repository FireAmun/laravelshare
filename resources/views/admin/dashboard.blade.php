@extends('admin.layout')

@section('content')
<div class="py-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Page header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
                    <p class="mt-2 text-sm text-gray-600">Welcome back! Here's what's happening with your file sharing service.</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-circle text-green-400 mr-2 text-xs"></i>
                        System Online
                    </span>
                    <span class="text-sm text-gray-500">{{ now()->format('M j, Y \a\t g:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- Stats overview -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <!-- Total Users -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200 hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                                <i class="fas fa-users text-white text-lg"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                <dd class="text-2xl font-bold text-gray-900">{{ number_format($stats['totalUsers']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-3">
                    <div class="text-sm">
                        <span class="text-blue-600 font-semibold">+{{ $stats['newUsersToday'] }}</span>
                        <span class="text-gray-600 ml-1">new today</span>
                    </div>
                </div>
            </div>

            <!-- Total Files -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200 hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-lg bg-gradient-to-r from-green-500 to-green-600 flex items-center justify-center">
                                <i class="fas fa-file-alt text-white text-lg"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Files</dt>
                                <dd class="text-2xl font-bold text-gray-900">{{ number_format($stats['totalFiles']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-3">
                    <div class="text-sm">
                        <span class="text-green-600 font-semibold">+{{ $stats['filesToday'] }}</span>
                        <span class="text-gray-600 ml-1">uploaded today</span>
                    </div>
                </div>
            </div>

            <!-- Storage Used -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200 hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-lg bg-gradient-to-r from-purple-500 to-purple-600 flex items-center justify-center">
                                <i class="fas fa-database text-white text-lg"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Storage Used</dt>
                                <dd class="text-2xl font-bold text-gray-900">{{ $storageStats['totalSizeFormatted'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 px-6 py-3">
                    <div class="text-sm">
                        <span class="text-purple-600 font-semibold">{{ number_format($storageStats['totalFiles']) }}</span>
                        <span class="text-gray-600 ml-1">files stored</span>
                    </div>
                </div>
            </div>

            <!-- Active Users -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200 hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-lg bg-gradient-to-r from-orange-500 to-orange-600 flex items-center justify-center">
                                <i class="fas fa-chart-line text-white text-lg"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Active Users</dt>
                                <dd class="text-2xl font-bold text-gray-900">{{ number_format($stats['activeUsers']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-orange-50 to-orange-100 px-6 py-3">
                    <div class="text-sm">
                        <span class="text-orange-600 font-semibold">{{ $stats['downloadsToday'] }}</span>
                        <span class="text-gray-600 ml-1">downloads today</span>
                    </div>
                </div>
            </div>
        </div>



        <!-- Recent Activity and Files -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Recent Users -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Users</h3>
                </div>
                <ul class="divide-y divide-gray-200">
                    @forelse($recentUsers as $user)
                        <li class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-600">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $user->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="px-6 py-4 text-center text-gray-500">No users found</li>
                    @endforelse
                </ul>
            </div>

            <!-- Recent Files -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Files</h3>
                </div>
                <ul class="divide-y divide-gray-200">
                    @forelse($recentFiles as $file)
                        <li class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 truncate max-w-xs">{{ $file->original_name }}</div>
                                        <div class="text-sm text-gray-500">
                                            @if($file->user)
                                                by {{ $file->user->name }}
                                            @else
                                                Anonymous
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $file->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="px-6 py-4 text-center text-gray-500">No files found</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- System Actions -->
        <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">System Actions</h3>
                <span class="text-sm text-gray-500">Quick administrative tasks</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <form method="POST" action="{{ route('admin.cleanup-expired-files') }}" class="inline">
                    @csrf
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transform hover:scale-105 transition-all duration-200">
                        <i class="fas fa-broom mr-2"></i>
                        Cleanup Expired Files
                    </button>
                </form>

                <a href="{{ route('admin.files') }}" class="w-full inline-flex items-center justify-center px-4 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-200 shadow-sm">
                    <i class="fas fa-file-alt mr-2"></i>
                    Manage Files
                </a>

                <a href="{{ route('admin.users') }}" class="w-full inline-flex items-center justify-center px-4 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-200 shadow-sm">
                    <i class="fas fa-users mr-2"></i>
                    Manage Users
                </a>
            </div>
        </div>
    </div>
</div>


@endsection
