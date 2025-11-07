@extends('layout.app')

@section('content')
    <div class="container py-5 px-3">
        <a href="{{ route('users') }}" class="link-style link-box d-flex justify-content-between m-auto mb-3">
            <div class="start-box d-flex gap-4">
                <div class="icon-box icon-wrapper d-flex align-items-center">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div class="box-details d-flex align-items-center">
                    Users
                </div>
            </div>

            <div class="icon-box d-flex align-items-center">
                <i class="fas fa-angle-right"></i>
            </div>
        </a>

        <a href="{{ route('audio.length') }}" class="link-style link-box d-flex justify-content-between m-auto mb-3">
            <div class="start-box d-flex gap-4">
                <div class="icon-box icon-wrapper d-flex align-items-center">
                    <i class="fa-solid fa-music"></i>
                </div>
                <div class="box-details d-flex align-items-center">
                    Find audio length
                </div>
            </div>

            <div class="icon-box d-flex align-items-center">
                <i class="fas fa-angle-right"></i>
            </div>
        </a>

        <a href="{{ route('distence') }}" class="link-style link-box d-flex justify-content-between m-auto mb-3">
            <div class="start-box d-flex gap-4">
                <div class="icon-box icon-wrapper d-flex align-items-center">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div class="box-details d-flex align-items-center">
                    Find Distence
                </div>
            </div>

            <div class="icon-box d-flex align-items-center">
                <i class="fas fa-angle-right"></i>
            </div>
        </a>

    </div>
@endsection
