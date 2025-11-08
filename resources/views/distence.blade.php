@extends('layout.app')

@section('content')
    <div class="container" style="max-width: 1200px !important;">
        <form action="{{ route('do.find.distence') }}" id="findDistenceForm" enctype="multipart/form-data" method="POST"
            class="creteuserform mt-5 p-4" autocomplete="off">
            @csrf
            <div class="form-group">
                <h4 class="h4">Find Distence</h4>
            </div>
            <div class="form-group mb-2">
                <label>From Latitude & Longitude</label>
                <div class="row">
                    <div class="col-6">
                        <input type="text" name="from_latitude" class="form-control" placeholder="Enter from latutude">
                        <span class="text-danger error from_latitude_error"></span>
                    </div>
                    <div class="col-6">
                        <input type="text" name="from_longitude" class="form-control" placeholder="Enter from longitude">
                        <span class="text-danger error from_longitude_error"></span>
                    </div>
                </div>
            </div>
            <div class="form-group mb-2">
                <label>To Latitude & Longitude</label>
                <div class="row">
                    <div class="col-6">
                        <input type="text" name="to_latitude" class="form-control" placeholder="Enter to latutude">
                        <span class="text-danger error from_latitude_error"></span>
                    </div>
                    <div class="col-6">
                        <input type="text" name="to_longitude" class="form-control" placeholder="Enter to longitude">
                        <span class="text-danger error to_longitude_error"></span>
                    </div>
                </div>
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
            $('#findDistenceForm').on('submit', function(e) {
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
                                $('#responseWrapper').removeClass('d-none');
                                $('#responseDiv').addClass('text-danger');
                                $('#responseDiv').html(res.msg);
                            }
                        } else {
                            $('#responseWrapper').removeClass('d-none');
                            $('#responseDiv').addClass('text-success');
                            $('#responseDiv').html(res.msg);
                        }
                    },
                    beforeSend: function() {
                        $('.error').empty();
                        $('#submitBtn').addClass('disabled');
                        $('#submitBtn').html('Please wait...');
                        $('#responseWrapper').addClass('d-none');
                        $('#responseDiv').removeClass('text-danger');
                        $('#responseDiv').removeClass('text-success');
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
