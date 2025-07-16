@extends('layout')

@section('title', 'About FileShare')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
    <!-- Hero Section -->
    <div class="text-center mb-16">
        <div class="mx-auto h-20 w-20 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center mb-8">
            <i class="fas fa-info-circle text-3xl text-white"></i>
        </div>
        <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl mb-4">
            About FileShare
        </h1>
        <p class="mx-auto max-w-2xl text-xl text-gray-600">
            A secure, modern file sharing platform built with cutting-edge technology and security in mind.
        </p>
    </div>

    <!-- Project Information -->
    <div class="grid grid-cols-1 gap-12 lg:grid-cols-2 mb-16">
        <!-- Project Overview -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <div class="flex items-center space-x-3 mb-6">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-lg">
                    <i class="fas fa-cloud-upload-alt text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Project Overview</h2>
                    <p class="text-sm text-gray-500">What FileShare is all about</p>
                </div>
            </div>

            <div class="space-y-4 text-gray-600">
                <p>
                    <strong class="text-gray-900">FileShare</strong> is a modern, secure file sharing platform inspired by services like WeTransfer.
                    It allows users to upload files and generate shareable links with advanced security features.
                </p>

                <div class="space-y-3">
                    <h3 class="font-semibold text-gray-900">Key Features:</h3>
                    <ul class="list-disc list-inside space-y-2 text-sm">
                        <li>Secure file uploads up to 5MB (optimized for free hosting)</li>
                        <li>Password protection for sensitive files</li>
                        <li>Automatic file expiration (1-30 days)</li>
                        <li>Download limits to prevent abuse</li>
                        <li>User authentication and file management</li>
                        <li>Admin security dashboard</li>
                        <li>Rate limiting and security monitoring</li>
                        <li>Mobile-responsive design</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Technical Stack -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <div class="flex items-center space-x-3 mb-6">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 text-white shadow-lg">
                    <i class="fas fa-code text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Technical Stack</h2>
                    <p class="text-sm text-gray-500">Technologies used in this project</p>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">Backend Technologies</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <i class="fab fa-laravel text-red-600"></i>
                            <span>Laravel 11</span>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <i class="fab fa-php text-purple-600"></i>
                            <span>PHP 8.x</span>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <i class="fas fa-database text-blue-600"></i>
                            <span>MySQL</span>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <i class="fas fa-shield-alt text-green-600"></i>
                            <span>Laravel Breeze</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">Frontend Technologies</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <i class="fab fa-css3-alt text-blue-600"></i>
                            <span>Tailwind CSS</span>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <i class="fab fa-js-square text-yellow-600"></i>
                            <span>Alpine.js</span>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <i class="fas fa-palette text-purple-600"></i>
                            <span>Blade Templates</span>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <i class="fas fa-icons text-indigo-600"></i>
                            <span>Font Awesome</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">Security Features</h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-lock text-green-600"></i>
                            <span>File validation and scanning</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-user-shield text-blue-600"></i>
                            <span>Rate limiting protection</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-eye text-purple-600"></i>
                            <span>Activity logging</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Developer Section -->
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border border-indigo-200 p-8 mb-12">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Meet the Developer</h2>
            <p class="text-lg text-gray-600">Passionate about creating secure, user-friendly web applications</p>
        </div>

        <div class="flex flex-col items-center space-y-6 lg:flex-row lg:space-y-0 lg:space-x-8">
            <!-- Developer Avatar -->
            <div class="flex-shrink-0">
                <div class="h-32 w-32 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white shadow-xl">
                    <span class="text-4xl font-bold">AH</span>
                </div>
            </div>

            <!-- Developer Info -->
            <div class="flex-1 text-center lg:text-left">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Amar Hassan Elshaer</h3>
                <p class="text-lg text-indigo-600 font-medium mb-4">Full-Stack Developer</p>

                <div class="space-y-3 text-gray-600">
                    <p>
                        A passionate full-stack developer with expertise in modern web technologies.
                        Specialized in creating secure, scalable, and user-friendly applications using Laravel, PHP, and modern frontend frameworks.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                        <div class="space-y-2">
                            <h4 class="font-semibold text-gray-900">Backend Expertise</h4>
                            <ul class="text-sm space-y-1">
                                <li>• Laravel & PHP Development</li>
                                <li>• RESTful API Design</li>
                                <li>• Database Architecture</li>
                                <li>• Security Implementation</li>
                            </ul>
                        </div>
                        <div class="space-y-2">
                            <h4 class="font-semibold text-gray-900">Frontend Skills</h4>
                            <ul class="text-sm space-y-1">
                                <li>• Modern CSS (Tailwind CSS)</li>
                                <li>• JavaScript & Alpine.js</li>
                                <li>• Responsive Design</li>
                                <li>• UI/UX Best Practices</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Goals -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
        <div class="text-center mb-8">
            <div class="mx-auto h-16 w-16 rounded-full bg-gradient-to-r from-green-500 to-emerald-600 flex items-center justify-center mb-4">
                <i class="fas fa-target text-2xl text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Project Goals & Vision</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="mx-auto h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center mb-4">
                    <i class="fas fa-shield-alt text-blue-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Security First</h3>
                <p class="text-sm text-gray-600">
                    Built with security as the top priority, implementing best practices for file handling and user data protection.
                </p>
            </div>

            <div class="text-center">
                <div class="mx-auto h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center mb-4">
                    <i class="fas fa-users text-green-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">User Experience</h3>
                <p class="text-sm text-gray-600">
                    Designed to be intuitive and easy to use, with a modern interface that works seamlessly across all devices.
                </p>
            </div>

            <div class="text-center">
                <div class="mx-auto h-12 w-12 rounded-lg bg-purple-100 flex items-center justify-center mb-4">
                    <i class="fas fa-code text-purple-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Modern Technology</h3>
                <p class="text-sm text-gray-600">
                    Leveraging the latest web technologies and frameworks to ensure performance, maintainability, and scalability.
                </p>
            </div>
        </div>

        <div class="mt-8 p-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg border border-indigo-200">
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Free Hosting Optimized</h3>
                <p class="text-sm text-gray-600">
                    This project is specifically optimized for free hosting services, with conservative resource usage
                    and efficient code structure to ensure smooth operation within hosting constraints.
                </p>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="text-center mt-12">
        <a href="{{ route('home') }}"
           class="inline-flex items-center rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-3 text-sm font-semibold text-white shadow-lg hover:shadow-xl hover:from-indigo-700 hover:to-purple-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all duration-200 transform hover:scale-105">
            <i class="fas fa-cloud-upload-alt mr-2"></i>
            Start Sharing Files
        </a>
    </div>
</div>
@endsection
