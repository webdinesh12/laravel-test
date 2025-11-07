@extends('layout.app')

@push('css')
    <style>
        .creteuserform {
            background: #fff;
            border-radius: 10px;
        }

        .creteuserform label {
            margin-bottom: 10px;
        }

        .submit-btn {
            border-radius: 0;
            background: #137fec;
            border: 0px;
            width: 100%;
            margin-top: 8px;
        }

        .profile-pic-preview {
            height: 100px;
            width: 100px;
            object-fit: cover;
            margin: auto;
            margin-bottom: auto;
            display: block;
            border: 1px solid #cecece;
            border-radius: 7px;
            margin-bottom: 15px;
        }

        .user img {
            height: 100px;
            width: 100px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .user p {
            margin-bottom: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <form action="{{ route('do.create.user') }}" id="createUserForm" enctype="multipart/form-data" method="POST"
            class="creteuserform mt-5 p-4" autocomplete="off">
            @if (session()->has('success'))
                <p class="text-success text-center">{{ session('success') }}</p>
            @endif
            @csrf
            <div class="form-group mb-2 d-none" id="profile_pic_wrapper">
                <img src="" class="profile-pic-preview">
            </div>
            <div class="form-group mb-2">
                <label for="profile_pic">Profile Picture: </label>
                <input type="file" name="profile_pic" class="form-control" id="profile_pic" accept=".jpg,.jpeg,.png">
                <span class="error text-danger profile_pic_error"></span>
            </div>
            <div class="form-group mb-2">
                <label for="name">Name: </label>
                <input type="text" name="name" class="form-control" id="name">
                <span class="error text-danger name_error"></span>
            </div>
            <div class="form-group mb-2">
                <label for="email">Email: </label>
                <input type="text" name="email" class="form-control" id="email">
                <span class="error text-danger email_error"></span>
            </div>
            <div class="form-group mb-2">
                <label for="mobile_no">Mobile No: </label>
                <input type="text" name="mobile_no" class="form-control" id="mobile_no">
                <span class="error text-danger mobile_no_error"></span>
            </div>
            <div class="form-group mb-2">
                <label for="password">Password: </label>
                <input type="password" name="password" class="form-control" id="password">
                <span class="error text-danger password_error"></span>
            </div>
            <div class="form-group mb-2">
                <button type="submit" class="btn btn-primary submit-btn" id="submitBtn">
                    Submit
                </button>
            </div>
        </form>

        <div class="users-list mt-5">
            <h5 class="h5 text-center mb-3">Users List</h5>
            @forelse ($users as $user)
                <div class="user mb-3">
                    <div class="row">
                        <div class="col-3">
                            <img src="{{ asset($user->image) }}" alt="{{ $user->name }}">
                        </div>
                        <div class="col-9">
                            <div class="details">
                                <p><strong>Name: </strong>{{ $user?->name ?? '-' }}</p>
                                <p><strong>Email: </strong>{{ $user?->email ?? '-' }}</p>
                                <p><strong>Mobile No.: </strong>{{ $user?->phone ?? '-' }}</p>
                            </div>
                        </div>
                    </div>


                </div>
            @empty
                <div class="no-records p-4">
                    <p class="text-center">No records found.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#profile_pic').on('change', function(e) {
                let file = e.target.files[0];
                if (file) {
                    let imgURL = URL.createObjectURL(file);
                    $('#profile_pic_wrapper').removeClass('d-none');
                    $('.profile-pic-preview').attr('src', imgURL);
                }
            });

            $('#createUserForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(e.target);
                $.ajax({
                    type: e.target.method,
                    url: e.target.action,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.success == 0) {
                            if (res.errors) {
                                for (let key in res.errors) {
                                    $(`.${key}_error`).html(res.errors[key]);
                                }
                            } else {
                                alert(res.msg || 'Something went wrong.');
                            }
                        } else {
                            window.location.reload();
                        }
                    },
                    beforeSend: function() {
                        $('.error').empty();
                        $('#submitBtn').addClass('disabled');
                        $('#submitBtn').html('Please wait...');
                    },
                    complete: function() {
                        $('#submitBtn').removeClass('disabled');
                        $('#submitBtn').html('Submit');
                    }
                });
            })
        });
    </script>
@endpush
