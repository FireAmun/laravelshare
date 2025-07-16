@extends('layout')

@section('title', 'Profile Settings')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="text-center mb-12">
        <div class="mx-auto h-20 w-20 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center mb-6">
            <i class="fas fa-user-edit text-3xl text-white"></i>
        </div>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-4">
            Profile Settings
        </h1>
        <p class="text-lg text-gray-600">
            Manage your account information and security settings
        </p>
    </div>

    <div class="space-y-8">
        <!-- Profile Information -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                <div class="flex items-center space-x-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-lg">
                        <i class="fas fa-user text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Profile Information</h2>
                        <p class="text-sm text-gray-600">Update your account's profile information and email address</p>
                    </div>
                </div>
            </div>
            <div class="p-8">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Update Password -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                <div class="flex items-center space-x-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 text-white shadow-lg">
                        <i class="fas fa-lock text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Update Password</h2>
                        <p class="text-sm text-gray-600">Ensure your account is using a long, random password to stay secure</p>
                    </div>
                </div>
            </div>
            <div class="p-8">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- Delete Account -->
        <div class="bg-white rounded-2xl shadow-lg border border-red-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-red-200 bg-gradient-to-r from-red-50 to-pink-50">
                <div class="flex items-center space-x-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-red-500 to-pink-600 text-white shadow-lg">
                        <i class="fas fa-trash-alt text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Delete Account</h2>
                        <p class="text-sm text-gray-600">Permanently delete your account and all of your data</p>
                    </div>
                </div>
            </div>
            <div class="p-8">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
