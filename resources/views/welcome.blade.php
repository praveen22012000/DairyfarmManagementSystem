<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Maruthi Dairy Farm Management System</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #3cb371;
            --primary-dark: #2e8b57;
            --text-color: #333;
            --light-gray: #f8f9fa;
        }
        
        body {
            background-color: var(--primary-color);
            font-family: 'Figtree', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--text-color);
        }
        
        .welcome-container {
            width: 100%;
            max-width: 420px;
            padding: 2rem;
        }
        
        .welcome-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 2.5rem;
            text-align: center;
        }
        
        .logo-container {
            margin-bottom: 1.5rem;
        }
        
        .logo {
            height: 80px;
            width: auto;
        }
        
        .title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--primary-dark);
        }
        
        .btn-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin: 2rem 0;
        }
        
        .btn {
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: block;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            border: 2px solid var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .btn-secondary {
            background-color: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }
        
        .btn-secondary:hover {
            background-color: var(--light-gray);
        }
        
        .tagline {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }
        
        .footer {
            margin-top: 2rem;
            color: white;
            font-size: 0.85rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <div class="welcome-card">
            <div class="logo-container">
                <!-- Replace with your actual logo path -->
                <img src="{{ asset('img/final_cow_logo.png') }}" alt="Maruthi Dairy Logo" class="logo">
            </div>
            
            <h1 class="title">Maruthi Dairy Farm Management System</h1>
            
            <p class="tagline">Efficient dairy farm management for modern agriculture</p>
            
            <div class="btn-container">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            Log In
                        </a>
                        
                     {{--   @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-secondary">
                                Register
                            </a>
                        @endif--}}
                    @endauth
                @endif
            </div>
        </div>
        
        <div class="footer">
            © {{ date('Y') }} Maruthi Dairy Farm. All rights reserved.
        </div>
    </div>
</body>
</html>