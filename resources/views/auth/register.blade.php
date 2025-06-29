<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dairy Farm Register</title>
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>
<body class="bg-gradient-primary">

    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="card o-hidden border-0 shadow-lg w-100" style="max-width: 900px;">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an User</h1>
                            </div>

                            <!-- Laravel Registration Form Starts -->
                            <form method="POST" action="{{ route('register') }}" class="user">
                                @csrf

                                <!-- Name -->
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control form-control-user" placeholder="First Name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Lastname -->
                                <div class="form-group">
                                    <input type="text" name="lastname" class="form-control form-control-user" placeholder="Last Name" value="{{ old('lastname') }}" required>
                                    @error('lastname')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control form-control-user" placeholder="Email Address" value="{{ old('email') }}" required>
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Address -->
                                <div class="form-group">
                                    <input type="text" name="address" class="form-control form-control-user" placeholder="Enter Address" value="{{ old('address') }}" required>
                                    @error('address')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Phone Number -->
                                <div class="form-group">
                                    <input type="text" name="phone_number" class="form-control form-control-user" placeholder="Enter phone number" value="{{ old('phone_number') }}" required>
                                    @error('phone_number')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- NIC Number -->
                                <div class="form-group">
                                    <input type="text" name="nic" class="form-control form-control-user" placeholder="Enter NIC number" value="{{ old('nic') }}" required>
                                    @error('nic')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Role Selection -->
                                <div class="form-group">
                                    <select name="role_id" class="form-control rounded-pill" required>
                                        <option value="">Select the role</option>
                                        @foreach ($roles as $i => $role)
                                            <option value="{{ $i }}" {{ old('role_id') == $i ? 'selected' : '' }}>{{ $role }}</option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Password + Confirm -->
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" name="password" class="form-control form-control-user" placeholder="Password" required>
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" name="password_confirmation" class="form-control form-control-user" placeholder="Repeat Password" required>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </button>
                            </form>
                            <!-- Laravel Registration Form Ends -->

                            <hr>
                            <div class="text-center">
                                <!-- Add links here if needed -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
</body>
</html>
