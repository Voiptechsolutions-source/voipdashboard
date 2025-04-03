<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Panel</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href= "{{ URL::asset('css/login.css'); }}" rel="stylesheet">
</head>
<body>

    <!-- New Login -->

    <div class="container-fluid vh-100 p-0">
        <div class="d-flex">
            <div class="d-none d-md-flex align-items-center justify-content-center login-bg w-75 vh-100">
                <img src="{{ asset('niceadmin/img/login-img.png') }}" alt="" />
            </div>
            <div class="login-box bg-white">
                <!-- Logo -->
                <div class="login-logo d-flex align-items-center pl-1 pr-5 gap-2 pt-3 mb-5">
                    <div class="logo-ig"><img src="{{ asset('niceadmin/img/voip-logo.png') }}" alt="" /></div>
                    
                </div>
                <!-- Welcome Message -->
                <div class="welcome-txt py-3 px-3">
                    <h3 class="font-weight-bold">Hi, Welcome Back!</h3>
                </div>
                <!-- Login Form -->
                <div class="login-form px-3 mt-5">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif
                <form method="POST" action="{{ route('logins') }}">
                    @csrf
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username"class="form-control" placeholder="Username" required >
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        
                        <!-- <div class="form-group">
                            <a href="#" class="text-dark">Forgot Password?</a>
                        </div> -->
                        <div class="form-group">
                            <button type="submit" name="login" class="btn btn-lg btn-login btn-block text-uppercase ">Log In</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card" style="max-width: 400px; width: 100%; padding: 30px; background-color: white; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <h2 class="text-center" style="margin-bottom: 30px;">Voip Solution<br>Login Panel</h2>
            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('logins') }}">
                @csrf
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
            </form>

            <p class="text-center" style="margin-top: 20px;">
                
            </p>
        </div>
    </div> -->
</body>
</html>
