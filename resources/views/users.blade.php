@extends('layout.app')

@section('content')
    <div class="container pt-5" style="max-width: 1200px !important;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="h4 mb-0">Users</h4>
            <div>
                <a href="{{ route('user.create') }}" class="btn btn-sm btn-outline-primary me-2">
                    Add New User
                </a>
                <a href="{{ route('user.export') }}" class="btn btn-sm btn-outline-success">
                    Export
                </a>
            </div>
        </div>
        <div class="card shadow-sm p-3">
            <table class="table table-hover align-middle users-table">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 15%;">Image</th>
                        <th style="width: 20%;">Name</th>
                        <th style="width: 25%;">Phone</th>
                        <th style="width: 25%;">Email</th>
                        <th style="width: 15%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>
                                <img class="user-image rounded-circle" src="{{ asset($user->image) }}"
                                    alt="{{ $user->name ?? '' }}" style="width: 55px; height: 55px; object-fit: cover;">
                            </td>
                            <td class="fw-semibold">
                                {{ $user->name ?? '-' }}
                            </td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>{{ $user->email ?? '-' }}</td>
                            <td>
                                <a href="{{ route('user.edit', ['id' => $user->id]) }}"
                                    class="btn btn-outline-secondary btn-sm me-1">
                                    Edit
                                </a>
                                <button class="btn btn-outline-danger btn-sm delete-user"
                                    data-route="{{ route('user.delete', ['id' => $user->id]) }}">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                No records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.delete-user').on('click', function() {
                let route = $(this).data('route');
                Swal.fire({
                    icon: "warning",
                    html: `Are you sure you want to delete the user?`,
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = route;
                    }
                });
            })
        });
    </script>
@endpush
