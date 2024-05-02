<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- CSS only -->
    <link href="{{ asset('public/css/login_form.css') }}" rel="stylesheet">

    <script type="text/javascript" src="{{ asset('public/js/app.js') }}"></script>

    <!-- jQuery Modal -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

    <title>LOGIN </title>

</head>
<body>
    <div class="login-page">
        @if ($errors->any())
            @foreach( $errors->all() as $error)
                <li> {{ $error }} </li>
            @endforeach
        @endif
        <div class="form">
            <form class="login-form" method="post" action="{{ url('login') }}">
                @csrf <!-- {{ csrf_field() }} -->
                <input type="email" id="email" name="email" placeholder="email"/>
                <input type="password" id="password" name="password" placeholder="password"/>
                <button>login</button>
{{--                <p class="message">Not registered? <a href="#">Create an account</a></p>--}}
            </form>
        </div>
    </div>
</body>
