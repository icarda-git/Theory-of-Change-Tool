@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>


                        <h2 class="text-divider"><span>Or</span></h2>

                        <div class="form-group row mb-0">
{{--                            LOGIN FROM OTHER SERVICES--}}
{{--                            <div class="col-md-8 offset-md-4">--}}

{{--                                <a id="orcid_login"--}}
{{--                                   href="https://orcid.org/oauth/authorize?client_id=APP-V7V5DM4314XLXUS6&response_type=code&scope=/authenticate&redirect_uri=https://dev2.scio.services/login/orcid/callback"--}}
{{--                                   class="btn btn-primary">--}}
{{--                                    Orcid Login--}}
{{--                                </a>--}}

{{--                                <a id="globus_login" href="https://auth.globus.org/v2/oauth2/authorize?scope=email+profile+openid&state=security_token%3D138r5719ru3e1%26url%3Dhttps://dev2.scio.services/&redirect_uri=https://dev2.scio.services/login/globus/callback&response_type=code&client_id=ef2f26d3-b37e-42ec-a4d7-5991f9904414 " class="btn btn-primary">--}}
{{--                                    Globus Login--}}
{{--                                </a>--}}

{{--                                <a id="globus_login" href="https://mel.cgiar.org/user/login/client_id/7" class="btn btn-primary">--}}
{{--                                    MEL Login--}}
{{--                                </a>--}}
{{--                            </div>--}}
{{--                            LOGIN FROM OTHER SERVICES--}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
