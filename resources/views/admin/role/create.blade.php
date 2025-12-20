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
                <h3 class="card-title m-0 flex-grow-1 text-center">Create Role</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.role.store') }}" method="POST" class="form-group">
                    @csrf
                    <div class="form-floating mb-3">
                        <input name="role" type="text" class="form-control" id="floating-input" value=""
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
                        >Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
