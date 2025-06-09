@extends('frontend.layouts.app')

@section('content')
    <div class="py-6 px-4 sm:py-12 sm:px-6">
        <div class="max-w-7xl mx-auto">
            {{-- Go back button--}}
            <div class="col-span-1 mb-4">
                <a href="{{ route('home.month') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-800 hover:bg-gray-900 rounded-md transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11H9v3H6v2h3v3h2v-3h3V9h-3V7z"/></svg>
                    {{ __('Go Back') }}
                </a>
            </div>

            <!-- Responsive grid: 1 column on mobile, 2 on tablet and up -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Update Profile Information -->
                <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="max-w-xl mx-auto">
                        @include('frontend.profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Update Password -->
                <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="max-w-xl mx-auto">
                        @include('frontend.profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Update Preferences -->
                <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 shadow rounded-lg md:col-span-2">
                    <div class="w-full">
                        @include('frontend.profile.partials.update-preferences-form')
                    </div>
                </div>

                <!-- Delete User -->
                <div class="px-4 py-2 md:col-span-2">
                    <div class="w-full border-b border-gray-200 dark:border-gray-700">
                        @include('frontend.profile.partials.delete-user-form')
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
