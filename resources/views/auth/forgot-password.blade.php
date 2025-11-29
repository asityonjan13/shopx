@extends('frontend.layouts.app')

@section('contents')
    <x-frontend.breadcrumb :items="[
     ['label' => 'Home', 'url' => '/'],
     ['label' => 'Forgot-Password']]"
     />

    <div class="page-content pt-20 pb-30">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-10 col-md-12 m-auto">
                    <div class="row">
                        <div class="col-lg-6 col-md-8 offset-lg-3">

                            <!-- Session Status -->
                            <x-auth-session-status class="mb-4" :status="session('status')" />

                            <div class="login_wrap widget-taber-content background-white">
                    <div class="padding_eight_all bg-white">
                        <div class="heading_s1">
                            <img class="border-radius-15" src="assets/imgs/page/forgot_password.svg" alt="" />
                            <h2 class="mb-15 mt-15">Forgot your password?</h2>
                            <p class="mb-30">Not to worry, we got you! Let’s get you a new password. Please
                                enter your email address or your Username.</p>
                        </div>
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="form-group">
                                <input id="email" class="block mt-1 w-full" type="email" name="email" placeholder="Email address" :value="old('email')" required autofocus />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-heading btn-block hover-up" name="login">
                                    {{ __('Email Password Reset Link') }}
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
