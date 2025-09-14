<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | CoffeeKo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .auth-box {
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

        .auth-link {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9em;
        }

        .auth-link a {
            color: #6b4f3b;
            text-decoration: none;
        }

        .auth-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="auth-box">
        <h1>@yield('title')</h1>

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- Page Content --}}
        @yield('content')
    </div>
</body>
</html>