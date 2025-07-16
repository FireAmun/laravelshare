@extends('admin.layout')

@section('content')
<div class="py-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Page header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
            <p class="mt-1 text-sm text-gray-600">Manage registered users and their permissions</p>
        </div>

        <!-- Users table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @forelse($users as $user)
                    <li class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-lg font-medium text-gray-600">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="flex items-center">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        @if($user->is_admin)
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                Admin
                                            </span>
                                        @endif
                                        @if(!$user->is_active)
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    <div class="text-xs text-gray-400">
                                        Joined {{ $user->created_at->format('M j, Y') }} •
                                        {{ $user->files_count }} files shared
                                        @if($user->last_login_at)
                                            • Last login {{ $user->last_login_at->diffForHumans() }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- User Actions -->
                            <div class="flex items-center space-x-2">
                                <!-- Toggle Status -->
                                <form method="POST" action="{{ route('admin.users.toggle-status', $user->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            title="{{ $user->is_active ? 'Deactivate' : 'Activate' }} User">
                                        @if($user->is_active)
                                            <svg class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                        @else
                                            <svg class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                    </button>
                                </form>

                                <!-- Admin Actions -->
                                @if($user->is_admin)
                                    <!-- Remove Admin -->
                                    @if(\App\Models\User::where('is_admin', true)->count() > 1)
                                        <form method="POST" action="{{ route('admin.users.remove-admin', $user->id) }}" class="inline"
                                              onsubmit="return confirm('Are you sure you want to remove admin privileges from this user?')">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1 border border-red-300 text-xs font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                    title="Remove Admin">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <!-- Make Admin -->
                                    <form method="POST" action="{{ route('admin.users.make-admin', $user->id) }}" class="inline"
                                          onsubmit="return confirm('Are you sure you want to make this user an admin?')">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1 border border-purple-300 text-xs font-medium rounded-md text-purple-700 bg-white hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                                                title="Make Admin">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <!-- Recent Files -->
                        @if($user->files->count() > 0)
                            <div class="mt-4 ml-16">
                                <div class="text-xs font-medium text-gray-500 mb-2">Recent Files:</div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($user->files->take(3) as $file)
                                        <div class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-gray-100 text-gray-700">
                                            <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                            </svg>
                                            {{ Str::limit($file->original_name, 20) }}
                                        </div>
                                    @endforeach
                                    @if($user->files->count() > 3)
                                        <div class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-blue-100 text-blue-700">
                                            +{{ $user->files->count() - 3 }} more
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </li>
                @empty
                    <li class="px-6 py-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No users found</h3>
                        <p class="mt-1 text-sm text-gray-500">There are no registered users in the system.</p>
                    </li>
                @endforelse
            </ul>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
