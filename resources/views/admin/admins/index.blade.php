@extends('admin.layouts.app')

@section('title', 'Admins')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Admins</h4>
        </div>

        <div class="text-end">
            <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">Add Admin</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admins as $admin)
                            <tr>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>
                                    @foreach ($admin->roles as $role)
                                        <span class="badge bg-primary-subtle text-primary">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('Delete this admin?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No admins yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
