@extends('layouts.guest')

@section('content')

<div class="container" id="container">
	<div class="form-container sign-up-container">
		<form  method="POST" action="{{route('register')}}">
            @csrf
			<h1>Create Account</h1>
			<div class="social-container">
				<a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
				<a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
				<a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
			</div>
			<span>or use your email for registration</span>
			<input id="name" name="name" type="text" placeholder="Name" />
            @error('name')
            <strong>
                {{$message}}
            </strong>
            @enderror
			<input id="email" name="email" type="email" placeholder="Email" />
             @error('email')
            <strong>
                {{$message}}
            </strong>
            @enderror
			<input id="password" name="password" type="password" placeholder="Password" />
            @error('password')
            <strong>
                {{$message}}
            </strong>
            @enderror
            <input id="password-confirm" name="password_confirmation" type="password" placeholder="Password" />
			<button type="submit">Sign Up</button>
		</form>
	</div>
	<div class="form-container sign-in-container">
		<form method="POST" action="{{route('login')}}">
            @csrf 
			<h1>Sign in</h1>
			<div class="social-container">
				<a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
				<a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
				<a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
			</div>
			
			<input value="{{old('email')}}" id="email" name="email" type="email" placeholder="Email" />
            @error('email')
            <strong>
                {{$message}}
            </strong>
            @enderror
			<input id="password" name="password" type="password" placeholder="Password" />
            @error('password')
            <strong>
                {{$message}}
            </strong>
            @enderror
            <input type="checkbox" name="remember" id="remember" {{old('remember')?'checked':''}}>
			<button type="submit">Sign in</button>
		</form>
	</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Welcome Back!</h1>
				<p>To keep connected with us please login with your personal info</p>
				<button class="ghost" id="signIn">Sign In</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>Hello, Friend!</h1>
				<p>Enter your personal details and start journey with us</p>
				<button class="ghost" id="signUp">Sign Up</button>
			</div>
		</div>
	</div>
</div>
@endsection
