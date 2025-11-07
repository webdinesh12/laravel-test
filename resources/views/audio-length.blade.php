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
        <form action="{{ route('do.audio.length') }}" id="createUserForm" enctype="multipart/form-data" method="POST"
            class="creteuserform mt-5 p-4" autocomplete="off">
            @csrf
            <div class="form-group">
                <h4 class="h4">Find Audio Length</h4>
            </div>
            <div class="form-group mb-2">
                <label for="audio">Audio File: </label>
                <input type="file" name="audio" class="form-control" id="audio" accept=".mp3,.wav,.m4a">
                <span class="error text-danger audio_error"></span>
            </div>
            <div class="form-group mb-2">
                <button type="submit" class="btn btn-primary submit-btn" id="submitBtn">
                    Submit
                </button>
            </div>
            <div class="form-group mt-3 d-none" id="responseWrapper">
                <p class="text-success text-center" id="responseDiv"></p>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                            $('#responseWrapper').removeClass('d-none');
                            $('#responseDiv').html(res.msg);
                        }
                    },
                    beforeSend: function() {
                        $('.error').empty();
                        $('#submitBtn').addClass('disabled');
                        $('#submitBtn').html('Please wait...');
                        $('#responseWrapper').addClass('d-none');
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
