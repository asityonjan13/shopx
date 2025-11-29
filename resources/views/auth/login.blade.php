@extends('frontend.layouts.app')

@section('contents')
    <x-frontend.breadcrumb :items="[['label' => 'Home', 'url' => '/'], ['label' => 'Login']]" />

    <div class="page-content pt-20 pb-30">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-10 col-md-12 m-auto">
                    <div class="row">
                        <div class="col-lg-6 pr-30 d-none d-lg-block">
                            <img class="border-radius-15" src="{{ asset('assets/frontend/imgs/page/login-1.png') }}"
                                alt="" />
                        </div>
                        <div class="col-lg-6 col-md-8">

                            <!-- Session Status -->
                            <x-auth-session-status class="mb-4" :status="session('status')" />

                            <div class="login_wrap widget-taber-content background-white">
                                <div class="padding_eight_all bg-white">

                                    <div class="heading_s1">
                                        <h1 class="mb-5">Login</h1>
                                        <p class="mb-30">
                                            Don't have an account?
                                            <a href="{{ route('register') }}">Create here</a>
                                        </p>
                                    </div>

                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf

                                        <!-- Email Address -->
                                        <div class="form-group">
                                            <input id="email" type="email" name="email" class="block mt-1 w-full"
                                                value="{{ old('email') }}" placeholder="Email" autofocus autocomplete="username" />

                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        </div>

                                        <!-- Password -->
                                        <div class="form-group">
                                            <input id="password" type="password" name="password" class="block mt-1 w-full"
                                                required autocomplete="current-password" placeholder="Password"/>

                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>

                                        <!-- Remember Me -->
                                        <div class="login_footer form-group mb-50">
                                            <div class="chek-form">
                                                <div class="custome-checkbox">
                                                    <input class="form-check-input" type="checkbox" name="remember"
                                                        id="exampleCheckbox1">

                                                    <label class="form-check-label" for="exampleCheckbox1">
                                                        <span>Remember me</span>
                                                    </label>
                                                </div>
                                            </div>

                                            @guest
                                                <a class="text-muted" href="{{ route('password.request') }}">Forgot
                                                    password?</a>
                                            @endguest

                                        </div>

                                        <!-- Submit -->
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-heading btn-block hover-up"
                                                name="login">
                                                Log in
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
