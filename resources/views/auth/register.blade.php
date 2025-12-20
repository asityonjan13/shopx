@extends('frontend.layouts.app')
@section('contents')
    <x-frontend.breadcrumb :items="[['label' => 'Home', 'url' => '/'], ['label' => 'Register']]" />
    <div class="page-content pt-20 pb-30">
        <div class="container">
            <div class="row">
                <div class="col-xxl-8 col-xl-10 col-lg-12 col-md-9 m-auto">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="login_wrap widget-taber-content background-white">
                                <div class="padding_eight_all bg-white">
                                    <div class="heading_s1">
                                        <h2 class="mb-5">Create an Account</h2>
                                        <p class="mb-30">Already have an account? <a href="{{ route('login') }}">Login</a>
                                        </p>
                                    </div>
                                    <form method="post" action="{{ route('register') }}">
                                        @csrf
                                        <div class="form-group">
                                            <input type="text" required="" name="name" placeholder="Full Name"
                                                value="{{ old('name') }}" />
                                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                        </div>
                                        <div class="form-group">
                                            <input type="email" required="" name="email" placeholder="Email"
                                                value="{{ old('email') }}" />
                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        </div>

                                        <div class="form-group">
                                            <input id="password" type="password" name="password" required
                                                autocomplete="new-password" placeholder="Password" />
                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>

                                        <div class="form-group">
                                            <input id="password_confirmation" type="password" name="password_confirmation"
                                                required autocomplete="new-password" placeholder="Confirm Password" />
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                        </div>

                                        <div class="payment_option mb-30">
                                            <div class="custome-radio">
                                                <input class="form-check-input" required type="radio" name="user_type"
                                                    id="exampleRadios1" checked value="user" />
                                                <label class="form-check-label" for="exampleRadios1"
                                                    data-bs-toggle="collapse" data-target="#bankTranfer"
                                                    aria-controls="bankTranfer">I am a customer</label>
                                            </div>
                                            <div class="custome-radio">
                                                <input class="form-check-input" required type="radio" name="user_type"
                                                    id="exampleRadios2" value="vendor" />
                                                <label class="form-check-label" for="exampleRadios2"
                                                    data-bs-toggle="collapse" data-target="#checkPayment"
                                                    aria-controls="checkPayment">I am a vendor</label>
                                            </div>
                                        </div>

                                        <div class="form-group mb-0">
                                            <button type="submit"
                                                class="btn btn-fill-out btn-block hover-up font-weight-bold"
                                                name="login">Submit &amp; {{ __('Register') }}r</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card-login">
                                <a href="#" class="social-login facebook-login">
                                    <img src="assets/imgs/theme/icons/logo-facebook.svg" alt="" />
                                    <span>Continue with Facebook</span>
                                </a>
                                <a href="#" class="social-login google-login">
                                    <img src="assets/imgs/theme/icons/logo-google.svg" alt="" />
                                    <span>Continue with Google</span>
                                </a>
                                <a href="#" class="social-login apple-login">
                                    <img src="assets/imgs/theme/icons/logo-apple.svg" alt="" />
                                    <span>Continue with Apple</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
