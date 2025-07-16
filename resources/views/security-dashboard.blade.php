@extends('layout')

@section('title', 'Security Dashboard')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Security Dashboard</h1>
        <p class="mt-2 text-gray-600">Monitor system security and user activity</p>
    </div>

    <!-- Security Stats -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <div class="glass-effect rounded-lg p-6 card-hover">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-red-100">
                        <i class="fas fa-shield-alt text-red-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Security Events (24h)</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $securityEvents }}</p>
                </div>
            </div>
        </div>

        <div class="glass-effect rounded-lg p-6 card-hover">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-yellow-100">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Failed Logins (24h)</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $failedLogins }}</p>
                </div>
            </div>
        </div>

        <div class="glass-effect rounded-lg p-6 card-hover">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100">
                        <i class="fas fa-upload text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">File Uploads (24h)</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $fileUploads }}</p>
                </div>
            </div>
        </div>

        <div class="glass-effect rounded-lg p-6 card-hover">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100">
                        <i class="fas fa-download text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Downloads (24h)</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $downloads }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Security Events -->
    <div class="glass-effect rounded-lg mb-8">
        <div class="px-6 py-4 border-b border-gray-200/20">
            <h2 class="text-lg font-semibold text-gray-900">Recent Security Events</h2>
        </div>
        <div class="p-6">
            @if($recentSecurityEvents->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Severity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($recentSecurityEvents as $event)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $event->created_at->format('M j, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ str_replace('_', ' ', ucwords($event->event)) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($event->severity === 'high') bg-red-100 text-red-800
                                            @elseif($event->severity === 'medium') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($event->severity) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $event->ip_address }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        @if($event->data)
                                            @php $data = json_decode($event->data, true) @endphp
                                            @if(isset($data['filename']))
                                                File: {{ $data['filename'] }}
                                            @elseif(isset($data['user_id']))
                                                User ID: {{ $data['user_id'] }}
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No security events recorded in the last 24 hours.</p>
            @endif
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="glass-effect rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200/20">
            <h2 class="text-lg font-semibold text-gray-900">Recent User Activity</h2>
        </div>
        <div class="p-6">
            @if($recentActivity->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($recentActivity as $activity)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $activity->created_at->format('M j, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($activity->user_id)
                                            {{ $activity->user->name ?? 'Unknown User' }}
                                        @else
                                            Guest
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if(str_contains($activity->action, 'upload')) bg-blue-100 text-blue-800
                                            @elseif(str_contains($activity->action, 'download')) bg-green-100 text-green-800
                                            @elseif(str_contains($activity->action, 'failed')) bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ str_replace('_', ' ', ucwords($activity->action)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $activity->ip_address }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        @if($activity->data)
                                            @php $data = json_decode($activity->data, true) @endphp
                                            @if(isset($data['filename']))
                                                {{ $data['filename'] }}
                                            @elseif(isset($data['file_uuid']))
                                                File ID: {{ substr($data['file_uuid'], 0, 8) }}...
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No recent activity recorded.</p>
            @endif
        </div>
    </div>
</div>
@endsection
