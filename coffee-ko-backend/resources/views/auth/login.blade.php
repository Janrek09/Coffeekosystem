<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | CoffeeKo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
        }

        h1 {
            margin-bottom: 20px;
            text-align: center;
        }

        input {
            padding: 10px;
            width: 100%;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background: #6b4f3b;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #5a3e2b;
        }

        .error {
            color: red;
            margin-bottom: 10px;
            font-size: 0.9em;
        }

        .register-link {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9em;
        }

        .register-link a {
            color: #6b4f3b;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>Login to CoffeeKo</h1>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <input type="email" name="email" placeholder="Email address" value="{{ old('email') }}" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <div class="register-link">
            Don't have an account? <a href="{{ route('register') }}">Register here</a>
        </div>
    </div>
</body>
</html>