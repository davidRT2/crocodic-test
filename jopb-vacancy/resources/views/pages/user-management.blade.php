@extends('layouts.app')

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'User Management'])

<div class="row mt-4 mx-4">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Users</h6>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#insertUserModal">
                    Add User
                </button>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Photo</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Create Date</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex px-3 py-1">
                                        <div>
                                            <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('img/bruce-mars.jpg') }}" class="avatar me-3" alt="image" style="width: 50px; height: 50px; object-fit: cover;">
                                        </div>

                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">{{ $user->username }}</h6>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-sm font-weight-bold mb-0">{{ $user->name }}</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <p class="text-sm font-weight-bold mb-0">{{ $user->created_at->format('d/m/Y') }}</p>
                                </td>
                                <td class="align-middle text-end">
                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                        <button type="button" class="btn btn-primary btn-sm me-2" onclick="editUser('{{ $user->id }}')">
                                            Edit
                                        </button>
                                        <a href="{{ route('profile.destroy', $user->id) }}" class="btn btn-danger btn-sm" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $user->id }}').submit();">Delete</a>
                                        <form id="delete-form-{{ $user->id }}" action="{{ route('profile.destroy', $user->id) }}" method="GET" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="insertUserModal" tabindex="-1" aria-labelledby="insertUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="insertUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('profile.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-user-form" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit-user-id" name="id">
                    <div class="mb-3">
                        <label for="edit-username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="edit-username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit-email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-password" class="form-label">Password (leave blank to keep current password)</label>
                        <input type="password" class="form-control" id="edit-password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="edit-photo" class="form-label">Photo</label>
                        <input type="file" class="form-control" id="edit-photo" name="photo" accept="image/*">
                    </div>
                    <!-- Current photo preview -->
                    <div class="mb-3">
                        <img id="current-photo" src="" alt="Current Photo" style="max-width: 100%; height: auto; display: none;">
                    </div>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </form>

            </div>
        </div>
    </div>
</div>




@endsection

<script>
    function editUser(id) {
        fetch(`/profile/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                // Mengisi data pengguna ke dalam form modal edit
                document.getElementById('edit-user-id').value = data.id;
                document.getElementById('edit-username').value = data.username;
                document.getElementById('edit-email').value = data.email;

                // Mengosongkan field password (tidak ada nilai default untuk keamanan)
                document.getElementById('edit-password').value = '';

                // Mengisi foto jika ada
                const photoElement = document.getElementById('current-photo');
                if (data.photo) {
                    const photoUrl = `/storage/${data.photo}`;
                    photoElement.src = photoUrl;
                    photoElement.style.display = 'block';
                } else {
                    photoElement.style.display = 'none';
                }

                // Update form action URL with the user id
                const form = document.getElementById('edit-user-form');
                form.action = `/profile-edit/${data.id}`;

                // Menampilkan modal edit
                new bootstrap.Modal(document.getElementById('editUserModal')).show();
            })
            .catch(error => console.error('Error:', error));
    }
</script>