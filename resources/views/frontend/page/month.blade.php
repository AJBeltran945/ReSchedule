@extends('frontend.layouts.app')

@section('content')
    <!-- Month view: only on large screens and up -->
    <div class="hidden lg:block">
        @livewire('month-calendar')
    </div>

    <!-- Week view: on screens smaller than lg -->
    <div class="block lg:hidden">
        @livewire('week-calendar')
    </div>
@endsection
