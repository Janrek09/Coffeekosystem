<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coffee-Ko Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <style>
    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: "Segoe UI", sans-serif;
      background-color: #f5e6da;
      color: #333;
      padding-bottom: 70px; /* para hindi matakpan ng tab bar */
    }

    h1, h2, h3, p { margin: 0; }

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

    .card-grid {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      gap: 12px;
      margin-bottom: 24px;
    }

    .card {
      background: #fff;
      border-radius: 12px;
      flex: 1 1 calc(50% - 12px);
      padding: 12px;
      display: flex;
      align-items: center;
      gap: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .card img { width: 36px; height: 36px; }
    .card-content { flex-grow: 1; }
    .card-title { font-size: 14px; font-weight: 600; color: #555; }
    .card-value { font-size: 18px; font-weight: 700; color: #6d4c41; }

    .chart-card {
      background: #fff;
      border-radius: 12px;
      padding: 16px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      margin-bottom: 24px;
    }

    .chart-title {
      font-size: 16px;
      font-weight: 700;
      color: #6d4c41;
      margin-bottom: 12px;
    }

    canvas {
      width: 100% !important;
      height: auto !important;
      max-height: 220px;
    }

    .best-sellers-list {
      list-style: none;
      padding: 0;
      margin-top: 12px;
    }
    .best-sellers-list li {
      padding: 6px 0;
      border-bottom: 1px solid #eee;
      font-weight: 600;
    }

    .menu-section { margin-top: 24px; }
    .menu-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
    }

    .menu-button {
      background-color: #6d4c41;
      color: white;
      flex: 1 1 calc(50% - 12px);
      padding: 16px;
      border-radius: 12px;
      text-align: center;
      cursor: pointer;
      font-weight: 600;
      text-decoration: none;
      display: block;
      transition: background 0.2s;
    }
    .menu-button:hover { background-color: #5a3e2b; }

    .calendar-container { margin-top: 16px; }
    .events-container { margin-top: 12px; }
    .event-item { margin-bottom: 6px; color: #333; font-weight: 500; }
    .no-events { font-style: italic; color: #777; }

    input[type="date"] {
      padding: 8px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 16px;
      max-width: 280px;
      width: 100%;
    }

    /* === Bottom Tab Bar === */
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
      display: block;
    }
    .tab-icon { display: block; font-size: 20px; }
    .tab.active { color: #6d4c41; font-weight: 600; }
  </style>
</head>
<body>
  <div class="container">
    <header>Coffee-Ko Dashboard</header>

    <!-- Summary Cards -->
    <section class="card-grid">
      <div class="card">
        <img src="https://cdn-icons-png.flaticon.com/512/924/924514.png" />
        <div class="card-content">
          <p class="card-title">Inventory</p>
          <p class="card-value" id="inventoryItems">-- items</p>
        </div>
      </div>
      <div class="card">
        <img src="https://cdn-icons-png.flaticon.com/512/590/590836.png" />
        <div class="card-content">
          <p class="card-title">Sales Today</p>
          <p class="card-value" id="salesToday">₱0</p>
        </div>
      </div>
      <div class="card">
        <img src="https://cdn-icons-png.flaticon.com/512/3081/3081559.png" />
        <div class="card-content">
          <p class="card-title">Products</p>
          <p class="card-value" id="productsTotal">-- total</p>
        </div>
      </div>
      <div class="card">
        <img src="https://cdn-icons-png.flaticon.com/512/2927/2927347.png" />
        <div class="card-content">
          <p class="card-title">Deliveries</p>
          <p class="card-value" id="pendingDeliveries">-- pending</p>
        </div>
      </div>
    </section>

    <!-- ✅ Ingredients / Inventory Card -->
    <section class="chart-card">
      <h2 class="chart-title">Ingredients</h2>
      <div id="ingredientsBox">
        <p class="text-gray-500">Loading ingredients...</p>
      </div>
    </section>

    <!-- Weekly Sales Chart -->
    <section class="chart-card">
      <h2 class="chart-title">Weekly Revenue</h2>
      <canvas id="weeklyRevenueChart"></canvas>
    </section>

    <!-- Best Sellers Chart -->
    <section class="chart-card">
      <h2 class="chart-title">Best Sellers</h2>
      <canvas id="bestSellersPie"></canvas>
      <ul class="best-sellers-list" id="bestSellersList"></ul>
    </section>

    <!-- Calendar -->
    <section class="chart-card">
      <h2 class="chart-title">Calendar</h2>
      <input type="date" id="datePicker" />
      <div class="events-container" id="eventsContainer">
        <p class="chart-title" id="eventsTitle">Select a date</p>
        <div id="eventsList">
          <p class="no-events">No events</p>
        </div>
      </div>
    </section>

    <!-- ✅ Quick Menu -->
    <section class="menu-section">
      <h2 class="chart-title">Quick Menu</h2>
      <div class="menu-grid">
        <a href="{{ url('/inventory') }}" class="menu-button">📦 Inventory</a>
        <a href="{{ url('/products') }}" class="menu-button">☕ Products</a>
        <a href="{{ url('/orders') }}" class="menu-button">🛒 Orders</a>
        <a href="{{ url('/deliveries') }}" class="menu-button">🚴 Deliveries</a>
      </div>
    </section>
  </div>

  <!-- ✅ Bottom Tabs -->
  <footer class="tab-bar">
    <a href="{{ url('/dashboard') }}" class="tab active">
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

  <script>
    // ✅ Load Ingredients dynamically from backend
    async function loadIngredients() {
      try {
        const res = await fetch("/api/dashboard/ingredients", {
          headers: { "Accept": "application/json" }
        });
        const data = await res.json();
        const container = document.getElementById("ingredientsBox");
        container.innerHTML = "";

        if (data.length === 0) {
          container.innerHTML = "<p class='no-events'>No ingredients found.</p>";
          return;
        }

        data.forEach(item => {
          const div = document.createElement("div");
          div.className = "ingredient-item";
          div.style.padding = "6px 0";
          div.style.borderBottom = "1px solid #eee";
          div.innerHTML = `
            <strong>${item.name}</strong>
            <span style="color:#666;"> - Stock: ${item.stock}</span>
            <span style="font-size:12px;color:#999;"> (${item.category})</span>
          `;
          container.appendChild(div);
        });
      } catch (err) {
        console.error("Error loading ingredients:", err);
      }
    }

    // ✅ Fetch dashboard summary from backend
    async function fetchDashboardData() {
      try {
        const res = await fetch("/dashboard/summary", {
          headers: { "Accept": "application/json" }
        });
        const data = await res.json();

        // Update Summary Cards
        document.getElementById("inventoryItems").innerText = data.inventoryItems ?? "--";
        document.getElementById("productsTotal").innerText = data.productsTotal ?? "--";
        document.getElementById("salesToday").innerText = `₱${data.salesToday ?? 0}`;
        document.getElementById("pendingDeliveries").innerText = `${data.pendingDeliveries ?? 0} pending`;

        // Update Weekly Revenue Chart
        if (data.weeklyRevenue) {
          updateRevenueChart(data.weeklyRevenue.labels, data.weeklyRevenue.data);
        }

        // Update Best Sellers
        if (data.bestSellers) {
          updateBestSellers(data.bestSellers);
        }
      } catch (err) {
        console.error("Error fetching dashboard summary:", err);
      }
    }

    function updateRevenueChart(labels, dataset) {
      const ctxRevenue = document.getElementById("weeklyRevenueChart").getContext("2d");
      new Chart(ctxRevenue, {
        type: "line",
        data: {
          labels: labels,
          datasets: [{
            label: "Revenue (₱)",
            data: dataset,
            backgroundColor: "rgba(109,76,65,0.2)",
            borderColor: "#6D4C41",
            borderWidth: 2,
            fill: true,
            tension: 0.3,
            pointBackgroundColor: "#6D4C41"
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
              ticks: { callback: val => `₱${val}` }
            }
          },
          plugins: { legend: { display: false } }
        }
      });
    }

    function updateBestSellers(bestSellers) {
      const ctxPie = document.getElementById("bestSellersPie").getContext("2d");
      const labels = Object.keys(bestSellers);
      const values = Object.values(bestSellers);
      const colors = ["#6D4C41","#A1887F","#F59E0B","#3B82F6","#10B981"];

      new Chart(ctxPie, {
        type: "pie",
        data: {
          labels: labels,
          datasets: [{
            data: values,
            backgroundColor: colors.slice(0, labels.length)
          }]
        },
        options: {
          plugins: {
            legend: {
              position: "bottom",
              labels: { color: "#333", font: { weight: "600", size: 14 } },
            },
          },
        },
      });

      const bestSellersList = document.getElementById("bestSellersList");
      bestSellersList.innerHTML = "";
      labels.forEach((name, i) => {
        const li = document.createElement("li");
        li.textContent = `${name} - ${values[i]} sales`;
        bestSellersList.appendChild(li);
      });
    }

    // Calendar events (dummy for now)
    const events = {
      "2025-09-05": [
        { title: "Supplier Delivery - Beans", time: "10:00 AM" },
        { title: "Team Meeting", time: "2:00 PM" },
      ],
      "2025-09-12": [
        { title: "Promo Launch - Mocha", time: "9:00 AM" },
        { title: "Inventory Check", time: "5:00 PM" },
      ],
    };

    const datePicker = document.getElementById("datePicker");
    const eventsTitle = document.getElementById("eventsTitle");
    const eventsList = document.getElementById("eventsList");

    datePicker.addEventListener("change", e => {
      const date = e.target.value;
      eventsList.innerHTML = "";
      const todaysEvents = events[date] || [];
      eventsTitle.textContent = `Events on ${date}`;
      if (todaysEvents.length === 0) {
        eventsList.innerHTML = '<p class="no-events">No events</p>';
      } else {
        todaysEvents.forEach(ev => {
          const div = document.createElement("div");
          div.className = "event-item";
          div.textContent = `📅 ${ev.time} - ${ev.title}`;
          eventsList.appendChild(div);
        });
      }
    });

    // Auto load on page load
    loadIngredients();
    fetchDashboardData();
    setInterval(loadIngredients, 30000);
    setInterval(fetchDashboardData, 60000);
  </script>
</body>
</html>
