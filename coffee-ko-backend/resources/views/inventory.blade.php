<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory - Coffee-Ko Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        .container { max-width: 860px; margin: 24px auto; padding: 0 16px; }
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
        .searchbar {
            margin: 12px 0;
            padding: 10px;
            width: 100%;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        .category-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-bottom: 24px;
        }
        .category-button {
            padding: 8px 16px;
            background-color: #6d4c41;
            color: white;
            border-radius: 20px;
            cursor: pointer;
        }
        .category-button.active { background-color: #5a3e2b; }
        .card {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            margin: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card.out-of-stock { background-color: #f2f2f2; opacity: 0.7; }
        .card-content { flex-grow: 1; display: flex; flex-direction: column; }
        .card-title { font-size: 16px; font-weight: bold; color: #333; }
        .card-quantity { font-size: 14px; color: #6d4c41; }
        .actions { display: flex; gap: 8px; align-items: center; }
        .button {
            padding: 6px 12px;
            border-radius: 6px;
            background-color: #6d4c41;
            color: white;
            cursor: pointer;
        }
        .delete-button { background-color: red; }
        .tab-bar {
            position: fixed; bottom: 0; left: 0; right: 0;
            height: 64px; background: #fff; border-top: 1px solid #ddd;
            display: flex; justify-content: space-around; align-items: center;
            box-shadow: 0 -2px 6px rgba(0,0,0,0.05); z-index: 999;
        }
        .tab { flex: 1; text-align: center; color: #888; font-size: 13px; font-weight: 500; cursor: pointer; padding: 4px 0; }
        .tab.active { color: #6d4c41; font-weight: 600; }
    </style>
</head>
<body>
    <div class="container">
        <header>Inventory Management</header>

        <!-- Search Bar -->
        <input class="searchbar" type="text" placeholder="Search ingredients..." id="searchQuery" />

        <!-- Category Filters -->
        <div class="category-container" id="categoryButtons">
            <button class="category-button active" data-category="All">All</button>
            <button class="category-button" data-category="Coffee">Coffee</button>
            <button class="category-button" data-category="Drinks">Drinks</button>
            <button class="category-button" data-category="Snacks">Snacks</button>
            <button class="category-button" data-category="Essentials">Essentials</button>
        </div>

        <!-- Inventory Cards -->
        <div id="inventoryList"></div>

        <!-- Add New Inventory Item -->
        <div style="text-align: center; margin-top: 24px;">
            <button class="button" onclick="addNewItem()">Add New Item</button>
        </div>
    </div>

    <!-- Bottom Tab Bar -->
    <footer class="tab-bar">
        <a href="{{ url('/dashboard') }}" class="tab">
            <span class="tab-icon">🏠</span>
            <span class="tab-label">Dashboard</span>
        </a>
        <a href="{{ url('/inventory') }}" class="tab active">
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
        let inventoryItems = [];
        let selectedCategory = "All";
        let searchQuery = "";

        async function fetchInventory() {
            const res = await fetch("/inventories");
            inventoryItems = await res.json();
            renderInventory();
        }

        function renderInventory() {
            const filtered = inventoryItems.filter(item =>
                (selectedCategory === "All" || item.category === selectedCategory) &&
                item.name.toLowerCase().includes(searchQuery.toLowerCase())
            );

            const list = document.getElementById("inventoryList");
            list.innerHTML = "";

            filtered.forEach(item => {
                const card = document.createElement("div");
                card.className = `card ${item.stock === 0 ? "out-of-stock" : ""}`;
                card.innerHTML = `
                    <div class="card-content">
                        <div class="card-title">${item.name} (${item.category})</div>
                        <div class="card-quantity">
                            ${item.stock === 0 ? "Out of Stock" : "Stock: " + item.stock} 
                            — ₱${parseFloat(item.price).toFixed(2)}
                        </div>
                    </div>
                    <div class="actions">
                        <button class="button" onclick="updateStock(${item.id}, -1)" ${item.stock === 0 ? "disabled" : ""}>-</button>
                        <button class="button" onclick="updateStock(${item.id}, 1)">+</button>
                        <button class="button delete-button" onclick="deleteItem(${item.id})">Delete</button>
                    </div>
                `;
                list.appendChild(card);
            });
        }

        document.getElementById("categoryButtons").addEventListener("click", e => {
            if (e.target.classList.contains("category-button")) {
                selectedCategory = e.target.dataset.category;
                renderInventory();
                document.querySelectorAll(".category-button").forEach(btn => btn.classList.remove("active"));
                e.target.classList.add("active");
            }
        });

        document.getElementById("searchQuery").addEventListener("input", e => {
            searchQuery = e.target.value;
            renderInventory();
        });

        async function updateStock(id, change) {
            const item = inventoryItems.find(i => i.id === id);
            if (!item) return;
            const newStock = Math.max(0, item.stock + change);
            await fetch(`/inventories/${id}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ stock: newStock })
            });
            fetchInventory();
        }

        async function deleteItem(id) {
            await fetch(`/inventories/${id}`, {
                method: "DELETE",
                headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content }
            });
            fetchInventory();
        }

        async function addNewItem() {
            const name = prompt("Enter item name:");
            const stock = parseInt(prompt("Enter stock:"), 10);
            const category = prompt("Enter category:");
            const price = parseFloat(prompt("Enter price:"));
            if (name && !isNaN(stock) && category && !isNaN(price)) {
                await fetch("/inventories", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ name, stock, category, price })
                });
                fetchInventory();
            }
        }

        fetchInventory();
    </script>
</body>
</html>
