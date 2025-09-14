<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Coffee-Ko Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .toggle-container {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }
        .toggle-btn {
            flex: 1;
            margin: 4px;
            padding: 8px 0;
            border: none;
            border-radius: 6px;
            background: #ddd;
            cursor: pointer;
            font-weight: 600;
        }
        .toggle-btn.active {
            background: #6d4c41;
            color: #fff;
        }
        .report-card {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            margin: 10px 0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 -2px 6px rgba(0,0,0,0.05);
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
    </style>
</head>
<body>
    <div class="container">
        <header>Reports</header>

        <!-- Toggle Buttons -->
        <div class="toggle-container">
            <button class="toggle-btn active" id="dailyBtn">Daily</button>
            <button class="toggle-btn" id="weeklyBtn">Weekly</button>
            <button class="toggle-btn" id="monthlyBtn">Monthly</button>
        </div>

        <!-- Report Cards -->
        <div id="reportCards">
            <div class="report-card">
                <h3>Total Sales</h3>
                <p>₱<span id="totalSales">0</span></p>
            </div>
            <div class="report-card">
                <h3>Transactions</h3>
                <p id="totalTransactions">0</p>
            </div>
        </div>

        <!-- Charts -->
        <div class="report-card">
            <h3>Sales Trend</h3>
            <canvas id="salesChart"></canvas>
        </div>
        <div class="report-card">
            <h3>Best Sellers</h3>
            <canvas id="bestSellersChart"></canvas>
        </div>
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
        <a href="{{ url('/reports') }}" class="tab active">
            <span class="tab-icon">📊</span>
            <span class="tab-label">Reports</span>
        </a>
        <a href="{{ url('/settings') }}" class="tab">
            <span class="tab-icon">⚙️</span>
            <span class="tab-label">Settings</span>
        </a>
    </footer>

    <script>
        // Sample Sales Data
        const salesData = [
            { date: "2025-08-15", product: "Latte", quantity: 5, price: 150 },
            { date: "2025-08-15", product: "Espresso", quantity: 3, price: 120 },
            { date: "2025-08-16", product: "Cappuccino", quantity: 4, price: 140 },
            { date: "2025-08-17", product: "Latte", quantity: 7, price: 150 },
            { date: "2025-08-17", product: "Mocha", quantity: 2, price: 160 },
            { date: "2025-08-18", product: "Espresso", quantity: 6, price: 120 },
        ];

        let salesChart, bestSellersChart;

        const aggregateData = (viewType) => {
            const grouped = {};
            for (const sale of salesData) {
                const revenue = sale.quantity * sale.price;
                let key = "";
                if (viewType === "daily") key = sale.date;
                else if (viewType === "weekly") {
                    const week = Math.ceil((new Date(sale.date).getDate()) / 7);
                    key = `Week ${week}`;
                } else if (viewType === "monthly") {
                    key = sale.date.slice(0, 7);
                }
                grouped[key] = (grouped[key] || 0) + revenue;
            }
            return grouped;
        };

        const aggregateBestSellers = () => {
            const totals = {};
            salesData.forEach(sale => {
                totals[sale.product] = (totals[sale.product] || 0) + (sale.quantity * sale.price);
            });
            return Object.entries(totals);
        };

        const renderCharts = (viewType) => {
            const aggregatedData = aggregateData(viewType);
            const labels = Object.keys(aggregatedData);
            const dataPoints = Object.values(aggregatedData);

            // Update totals
            document.getElementById("totalSales").innerText = dataPoints.reduce((a,b) => a+b, 0).toFixed(2);
            document.getElementById("totalTransactions").innerText = salesData.length;

            // Destroy old charts
            if (salesChart) salesChart.destroy();
            if (bestSellersChart) bestSellersChart.destroy();

            // Sales Chart
            salesChart = new Chart(document.getElementById("salesChart"), {
                type: "line",
                data: {
                    labels,
                    datasets: [{
                        label: "Sales (₱)",
                        data: dataPoints,
                        backgroundColor: "rgba(109,76,65,0.2)",
                        borderColor: "#6D4C41",
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true, ticks: { callback: v => `₱${v}` } }
                    }
                }
            });

            // Best Sellers
            const bestSellers = aggregateBestSellers();
            bestSellersChart = new Chart(document.getElementById("bestSellersChart"), {
                type: "pie",
                data: {
                    labels: bestSellers.map(([name]) => name),
                    datasets: [{
                        data: bestSellers.map(([_, rev]) => rev),
                        backgroundColor: ["#6D4C41", "#A1887F", "#F59E0B", "#3B82F6", "#10B981"]
                    }]
                }
            });
        };

        // Toggle buttons
        document.querySelectorAll(".toggle-btn").forEach(btn => {
            btn.addEventListener("click", () => {
                document.querySelectorAll(".toggle-btn").forEach(b => b.classList.remove("active"));
                btn.classList.add("active");
                renderCharts(btn.id.replace("Btn", "").toLowerCase());
            });
        });

        // Initial
        renderCharts("daily");
    </script>
</body>
</html>
