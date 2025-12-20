@extends('admin.layouts.app')

@section('contents')
    <div class="container-xl">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <a href="{{ url()->previous() }}" class="text-muted me-3" style="text-decoration: none; transition: all 0.2s;"
                    onmouseover="this.style.transform='translateX(-3px)'" onmouseout="this.style.transform='translateX(0)'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon">
                        <path d="M5 12l14 0"></path>
                        <path d="M5 12l6 6"></path>
                        <path d="M5 12l6 -6"></path>
                    </svg>
                </a>
                <h3 class="card-title m-0 flex-grow-1 text-center">All Roles</h3>

                <label class="form-selectgroup-item">
                    <input type="radio" name="icons" value="user" class="form-selectgroup-input">
                    <a href="{{ route('admin.role.create') }}">
                        <span
                            class="form-selectgroup-label"><!-- Download SVG icon from http://tabler.io/icons/icon/user -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon me-1 icon-3">
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                            </svg> Create Role
                        </span>
                    </a>
                </label>
            </div>
            <div class="card-body">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Roll Name</th>
                                    <th>Permissions</th>
                                    <th class="w-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roles as $role)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-secondary">{{ $role->name }}</td>
                                        <td class="text-secondary"><span
                                                class="badge bg-success-lt">{{ $role?->permissions_count }}</span></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                @if ($role->name != "Super Admin")
                                                <a href="{{ route('admin.role.edit', $role) }}" class="btn btn-success">
                                                    Edit
                                                </a>
                                                <a href="{{ route('admin.role.destroy', $role) }}"
                                                    class="delete-item btn btn-danger">
                                                    Delete
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No any Roles</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        {{-- {{ $roles->links() }} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
