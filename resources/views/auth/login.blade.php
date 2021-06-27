@extends('layouts.auth_layout')
@section('content')
<div class="login">
    <form class="js-login__form login__form" action="{{ route('login') }}" method="POST">
        @csrf
        <h1 class="fw-light">Photos</h1>
        <input required placeholder="Email" name="email" class="mb-3 form-control form-control-lg" type="text">
        <input required placeholder="Password" name="password" class="mb-3 form-control form-control-lg"
            type="password">
        <input type="hidden" name="remember" value="1" />
        <button type="submit" class="login__submit-btn btn btn-lg btn-primary">
            Enter
        </button>
    </form>

</div>
@endsection
