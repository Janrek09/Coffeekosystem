<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registered Routes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 2rem;
            background-color: #f9f9f9;
        }

        h1 {
            color: #2c3e50;
        }

        .logout-form {
            text-align: right;
            margin-bottom: 20px;
        }

        .logout-form form {
            display: inline;
        }

        .logout-form button {
            background-color: #c0392b;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .logout-form button:hover {
            background-color: #a93226;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background: #fff;
            border: 1px solid #ddd;
            padding: 1rem;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        strong {
            color: #2c3e50;
        }

        em {
            color: #7f8c8d;
        }
    </style>
</head>
<body>

    <div class="logout-form">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>

    <h1>List of Registered Routes</h1>

    <ul>
        @foreach ($routes as $route)
            <li>
                <strong>{{ $route['method'] }}</strong> - {{ $route['uri'] }}
                <em> - {{ $route['name'] ?? 'No name' }}</em>
            </li>
        @endforeach
    </ul>

</body>
</html>
