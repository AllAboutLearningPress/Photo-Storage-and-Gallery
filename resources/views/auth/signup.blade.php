@extends('layouts.auth_layout')
@section('title', 'Create New Account')
@section('content')
<div class="login">
    <form class="js-login__form login__form" action="{{ $store_link }}" method="POST">
        @csrf
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <h1 class="fw-light">Sign Up</h1>
        <input type="hidden" value="{{ $code }}" name="code">
        <input required placeholder="Name" name="name" class="mb-3 form-control form-control-lg" type="text">
        <input class="mb-3 form-control form-control-lg" disabled placeholder="Email" name="email" value="{{ $email }}"
            type="text">

        <input required placeholder="Password" name="password" id="password123"
            class="mb-3 form-control form-control-lg password" type="password" autocomplete="new-password">
        <div class="password__info">Password should contain atleast one small case, one capital case, one number and one
            special character
        </div>
        <input required placeholder="Confirm password" name="confirm-password" id="confirm-password123"
            class="mb-3 form-control form-control-lg" type="password" id="confirm" autocomplete="new-password">
        <input type="hidden" name="remember" value="1" />
        <button type="submit" class="login__submit-btn btn btn-lg btn-primary">
            Signup
        </button>
    </form>
</div>
@endsection
@section('scripts')
<script>
    const validateForm = function(e){
        var password = document.querySelector("#password");
        var confirmPassword = document.querySelector("#confirm_password");
        if (password.value != confirmPassword.value){
            confirmPassword.setCustomValidity("Password do not match");
            e.preventDefault();
            e.stopPropagation();
        }
    }

    var signupForm = document.querySelector(".login__form");
    signupForm.addEventListener('submit', validateForm )
</script>
@endsection
