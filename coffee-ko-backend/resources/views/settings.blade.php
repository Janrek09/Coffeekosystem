<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Settings - Coffee-Ko Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
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

        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .button {
            padding: 15px;
            background-color: #f5f5f5;
            border-radius: 10px;
            margin-bottom: 15px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #e0d3c3;
        }

        .buttonText {
            font-size: 16px;
            color: #333;
        }

        .logout {
            color: red;
        }

        .tab-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 64px;
            background: #fff;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-around;
            align-items: center;
            box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.05);
            z-index: 999;
        }

        .tab {
            flex: 1;
            text-align: center;
            color: #888;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            padding: 4px 0;
            text-decoration: none;
        }

        .tab.active {
            color: #6d4c41;
            font-weight: 600;
        }

        /* Success message styling */
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 16px;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>Settings</header>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="title">Settings</div>

        <!-- Settings Buttons -->
        <div class="button" onclick="window.location.href='{{ url('/profile') }}'">
            <span class="buttonText">Profile</span>
        </div>

        <div class="button" onclick="window.location.href='{{ url('/notifications') }}'">
            <span class="buttonText">Notifications</span>
        </div>

        <!-- Logout Button with POST -->
        <div class="button logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <span class="buttonText">Logout</span>
        </div>

        <!-- Logout Form -->
        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <!-- Bottom Tab Bar -->
    <footer class="tab-bar">
        <a href="{{ url('/dashboard') }}" class="tab">
            <span class="tab-icon">🏠</span>
            <span class="tab-label">Dashboard</span>
        </a>
        <a href="{{ url('/inventory') }}" class="tab">
            <span class="tab-icon">📦</span>
            <span class="tab-label">Inventory</span>
        </a>
        <a href="{{ url('/reports') }}" class="tab">
            <span class="tab-icon">📊</span>
            <span class="tab-label">Reports</span>
        </a>
        <a href="{{ url('/settings') }}" class="tab active">
            <span class="tab-icon">⚙️</span>
            <span class="tab-label">Settings</span>
        </a>
    </footer>
</body>
</html>

