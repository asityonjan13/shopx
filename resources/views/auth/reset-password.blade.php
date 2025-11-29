
@extends('frontend.layouts.app')

@section('contents')
    <x-frontend.breadcrumb :items="[['label' => 'Home', 'url' => '/'], ['label' => 'Login']]" />

    <div class="page-content pt-20 pb-30">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-10 col-md-12 m-auto">
                    <div class="row">
                        <div class="col-lg-6 col-md-8 offset-lg-3">
                            <div class="login_wrap widget-taber-content background-white">
                                <div class="padding_eight_all bg-white">

                                    <div class="heading_s1">
                                        <h4 class="mb-5">Reset Password</h>
                                    </div>

                                    <form method="POST" action="{{ route('password.store') }}">
                                        @csrf

                                        <!-- Password Reset Token -->
                                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                        <!-- Email Address -->
                                        <div class="form-group">
                                            <input id="email" type="email" name="email" class="block mt-1 w-full"
                                                value="{{ old('email', $request->email) }}" placeholder="Email" autofocus
                                                autocomplete="username" />
                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        </div>

                                        <!-- Password -->
                                        <div class="form-group">
                                            <input id="password" type="password" name="password" class="block mt-1 w-full"
                                                required autocomplete="current-password" placeholder="Password" />

                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>
                                        <!-- Confirm Password -->
                                        <div class="form-group">
                                            <input id="password" type="password" name="password_confirmation"
                                                class="block mt-1 w-full" required autocomplete="current-password"
                                                placeholder="Confirm Password" />

                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                        </div>

                                        <!-- Submit -->
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-heading btn-block hover-up"
                                                name="login">
                                                {{ __('Reset Password') }}
                                            </button>
                                        </div>

                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
