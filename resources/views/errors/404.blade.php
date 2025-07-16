@extends('layout')

@section('title', 'File Not Found')

@section('content')
<div class="max-w-md mx-auto text-center">
    <div class="bg-white rounded-lg shadow-md p-8">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-6">
            <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-4">File Not Available</h1>

        <p class="text-gray-600 mb-6">
            This file may have expired, reached its download limit, or been removed.
        </p>

        <a href="{{ route('home') }}"
           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-upload mr-2"></i>
            Upload a New File
        </a>
    </div>
</div>
@endsection
