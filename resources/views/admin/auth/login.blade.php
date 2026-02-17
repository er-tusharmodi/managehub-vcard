<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Admin Login | ManageHub</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="ManageHub admin login" />
        <meta name="author" content="ManageHub" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <link rel="shortcut icon" href="{{ asset('backendtheme/assets/images/favicon.ico') }}">
        <link href="{{ asset('backendtheme/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
        <link href="{{ asset('backendtheme/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    </head>

    <body data-menu-color="light" data-sidebar="default">
        <div class="account-pages my-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="text-center mb-4">
                                    <h4 class="text-uppercase mt-0">Admin Sign In</h4>
                                    <p class="text-muted">Access the ManageHub admin panel</p>
                                </div>

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        {{ $errors->first() }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('admin.login.store') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="emailaddress" class="form-label">Email address</label>
                                        <input class="form-control" type="email" id="emailaddress" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email">
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input class="form-control" type="password" id="password" name="password" required placeholder="Enter your password">
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                            <label class="form-check-label" for="remember">Remember me</label>
                                        </div>
                                    </div>

                                    <div class="mb-0 text-center">
                                        <button class="btn btn-primary w-100" type="submit">Log In</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('backendtheme/assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('backendtheme/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('backendtheme/assets/libs/feather-icons/feather.min.js') }}"></script>
        <script src="{{ asset('backendtheme/assets/js/app.js') }}"></script>
    </body>
</html>
