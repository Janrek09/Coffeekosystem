<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Coffee-Ko Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Segoe UI", sans-serif;
            background-color: #f5e6da;
            color: #333;
            padding-bottom: 70px;
        }

        .container {
            max-width: 860px;
            margin: 24px auto;
            padding: 0 16px;
        }

        header {
            background-color: #6d4c41;
            padding: 16px;
            color: white;
            font-size: 22px;
            font-weight: 600;
            border-radius: 12px;
            margin-bottom: 24px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        .button {
            padding: 12px 24px;
            background-color: #6d4c41;
            color: white;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            transition: background-color 0.2s;
        }

        .button:hover {
            background-color: #5a3e2b;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        .success {
            color: green;
            font-weight: bold;
            margin-bottom: 16px;
            padding: 10px;
            background: #e7f5e8;
            border-left: 4px solid green;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>My Profile</header>

        {{-- Success message --}}
        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Profile Update Form --}}
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf

            {{-- Name --}}
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Submit --}}
            <button type="submit" class="button">Update Profile</button>
        </form>
    </div>
</body>
</html>
