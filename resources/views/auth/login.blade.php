@extends('layouts.app')
@section('page')
    {{ "Login" }}
@endsection
@section('content')
<div class="container">

@if ($errors->has('session'))
    <div class="alert alert-danger">{{ $errors->first('session') }}</div>
@endif


    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg" style="border-radius: 1.25rem !important;">
                <div class="card-header border-0"><h4>{{ __('Login') }}</h4></div>

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
                                <a id="linkedin-button" class="btn btn-primary" href="{{ route('linkedin_redirect') }}">
                                  <i class="fab fa-linkedin-in" aria-hidden="true">&nbsp&nbsp|</i>
                                   Connect with Linkedin
                                </a>

                            </div>
                        </div>
                        <div class="form-group row mt-1 mb-0">
                            <div class="col-md-8 offset-md-4">
                                 @if (Route::has('password.request'))
                                    <a class="btn btn-link" style="margin-left: -10px;" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif

                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
$(document).ready(function(){

    if(localStorage.getItem("status"))
    {
        $.toaster({ priority : 'success', title : 'Success', message : localStorage.getItem("message")});
        localStorage.clear();
    }
});
</script>
@endpush
