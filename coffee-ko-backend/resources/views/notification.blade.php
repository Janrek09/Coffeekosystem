<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Coffee-Ko Dashboard</title>
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

        .notification {
            background: #fff;
            border-radius: 8px;
            padding: 16px;
            margin: 10px 0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .notification p {
            margin: 0;
            font-size: 16px;
        }

        .notification-time {
            font-size: 12px;
            color: #888;
        }

        .no-notifications {
            text-align: center;
            font-size: 18px;
            color: #888;
            margin-top: 50px;
        }

        .tab-bar {
            position: fixed;
            bottom: 0; left: 0; right: 0;
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
        }

        .tab.active {
            color: #6d4c41;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>Notifications</header>

        <!-- Notifications Section -->
        @if(count($notifications) > 0)
            @foreach($notifications as $notification)
                <div class="notification">
                    <p>{{ $notification->data['message'] }}</p>
                    <p class="notification-time">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</p>
                </div>
            @endforeach
        @else
            <p class="no-notifications">No new notifications at the moment.</p>
        @endif
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
        <a href="{{ url('/settings') }}" class="tab">
            <span class="tab-icon">⚙️</span>
            <span class="tab-label">Settings</span>
        </a>
    </footer>
</body>
</html>
