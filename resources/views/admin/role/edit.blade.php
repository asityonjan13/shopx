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
                <h3 class="card-title m-0 flex-grow-1 text-center">Update Role</h3>

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
                <form action="{{ route('admin.role.update', $role) }}" method="POST" class="form-group">
                    @csrf
                    @method('PUT')
                    <div class="form-floating mb-3">
                        <input name="role" type="text" class="form-control" id="floating-input" value="{{ $role->name }}"
                            autocomplete="off">
                        <label for="floating-input">Role Name</label>
                        <x-input-error :messages="$errors->get('role')" class="mt-2"/>
                    </div>
                    <div class="row">
                        @foreach($permissions as $groupName => $permission)
                            <div class="col-md-4 mb3">
                                <h3>{{ $groupName }}</h3>
                                @foreach($permission as $item)
                                <label for="permissions[]" class="form-check">
                                    <input class="form-check-input"
                                    @checked($role->hasPermissionTo($item->name))
                                    type="checkbox"
                                    value="{{ $item->name }}"
                                    name="permissions[]">
                                    <span class="form-check-label">{{ $item->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        @endforeach

                    </div>
                    <div class="text-end">
                        <button type="submit"
                        class="btn btn-primary"
                        onclick="$('form').submit()"
                        >Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
