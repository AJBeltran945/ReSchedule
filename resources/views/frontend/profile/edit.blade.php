@extends('frontend.layouts.app')

@section('content')
    <div class="py-6 px-4 sm:py-12 sm:px-6">
        <div class="max-w-7xl mx-auto">
            <!-- Responsive grid: 1 column on mobile, 2 on tablet and up -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Update Profile Information -->
                <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="max-w-xl mx-auto">
                        @include('frontend.profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Update Preferences -->
                <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="max-w-xl mx-auto">
                        @include('frontend.profile.partials.update-preferences-form')
                    </div>
                </div>

                <!-- Update Password -->
                <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="max-w-xl mx-auto">
                        @include('frontend.profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Delete User -->
                <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="max-w-xl mx-auto">
                        @include('frontend.profile.partials.delete-user-form')
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
