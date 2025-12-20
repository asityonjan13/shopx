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
                <h3 class="card-title m-0 flex-grow-1 text-center">Create User</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.role-users.store') }}" method="POST" class="card">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label class="col-3 col-form-label required">Full Name</label>
                            <div class="col">
                                <input name="name" type="text" class="form-control" aria-describedby="emailHelp"
                                    placeholder="Enter Name">
                                <small class="form-hint">We'll never share your email with anyone else.</small>
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-3 col-form-label required">Email address</label>
                            <div class="col">
                                <input name="email" type="text" class="form-control" aria-describedby="emailHelp"
                                    placeholder="Enter email">
                                <small class="form-hint">We'll never share your email with anyone else.</small>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-3 col-form-label required">Password</label>
                            <div class="col">
                                <input name="password" type="password" class="form-control" placeholder="Password">
                                <small class="form-hint">
                                    Your password must be 8-20 characters long, contain letters and numbers, and must not
                                    contain spaces, special characters, or
                                    emoji.
                                </small>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-3 col-form-label required">Confirm Password</label>
                            <div class="col">
                                <input name="password_confirmation" type="password" class="form-control"
                                    placeholder="Password">
                                <small class="form-hint">
                                    Your password must be 8-20 characters long, contain letters and numbers, and must not
                                    contain spaces, special characters, or
                                    emoji.
                                </small>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-3 col-form-label">Select Roles</label>
                            <div class="col">
                                <select name="role" class="form-select">
                                    @foreach ($roles as $role)
                                    @if ($role->name=="Super Admin") @continue @endif
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary" onclick="$('form').submit()">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
