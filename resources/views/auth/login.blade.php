<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Maruthi Dairy - Login</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <style>
        .bg-custom-primary {
            background-color: #3cb371 !important;
            background: linear-gradient(180deg, #3cb371 0%, #2e8b57 100%) !important;
        }
        .btn-custom-primary {
            background-color: #3cb371;
            border-color: #3cb371;
            color: white;
        }
        .btn-custom-primary:hover {
            background-color: #2e8b57;
            border-color: #2e8b57;
            color: white;
        }
        .card {
            border-radius: 10px;
        }
        .form-control-user {
            border-radius: 50px;
        }
    </style>
</head>

<body class="bg-custom-primary">

    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="col-xl-6 col-lg-7 col-md-9">
            <div class="card o-hidden border-0 shadow-lg">
                <div class="card-body p-0">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Welcome to Maruthi Dairy!</h1>
                        </div>
                        <form class="user" method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group">
                                <input type="email" name="email" class="form-control form-control-user"
                                       id="email" required placeholder="Enter Email Address...">
                                @error('email')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <input type="password" name="password" id="password"
                                       class="form-control form-control-user" placeholder="Password">
                                @error('password')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" class="custom-control-input" id="customCheck">
                                    <label class="custom-control-label" for="customCheck">Remember Me</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-custom-primary btn-user btn-block">
                                Login
                            </button>

                            <hr>
                        </form>
                        <div class="text-center">
                            <a class="small" href="forgot-password.html">Forgot Password?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

</body>

</html>