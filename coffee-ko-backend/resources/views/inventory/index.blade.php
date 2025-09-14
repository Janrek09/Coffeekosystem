<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory - Coffee-Ko Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            margin: 0;
            background-color: #fdf6f0;
            color: #3e2c23;
            padding-bottom: 70px;
        }
        .container {
            max-width: 860px;
            margin: 24px auto;
            padding: 0 16px;
        }
        header {
            background-color: #4e342e;
            padding: 20px;
            color: #fffbe7;
            font-size: 26px;
            font-weight: 600;
            border-radius: 16px;
            margin-bottom: 24px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .searchbar {
            margin: 16px 0;
            padding: 12px;
            width: 100%;
            border-radius: 10px;
            border: 1px solid #d7ccc8;
            background-color: #fffdf8;
        }
        .category-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
            margin-bottom: 24px;
        }
        .category-button {
            padding: 10px 18px;
            background-color: #a1887f;
            color: white;
            border-radius: 24px;
            cursor: pointer;
            border: none;
            transition: background 0.2s;
        }
        .category-button:hover {
            background-color: #8d6e63;
        }
        .category-button.active {
            background-color: #5d4037;
        }
        .card {
            background: #ffffff;
            border-radius: 14px;
            padding: 18px;
            margin: 12px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        .card.out-of-stock {
            background-color: #fbe9e7;
            opacity: 0.7;
        }
        .card-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #4e342e;
        }
        .card-quantity {
            font-size: 14px;
            color: #6d4c41;
        }
        .actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        .button {
            padding: 6px 12px;
            border-radius: 8px;
            background-color: #6d4c41;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .button:hover {
            background-color: #5d4037;
        }
        .delete-button {
            background-color: #d84315;
        }
        .delete-button:hover {
            background-color: #bf360c;
        }
        .tab-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 64px;
            background: #fffbe7;
            border-top: 1px solid #d7ccc8;
            display: flex;
            justify-content: space-around;
            align-items: center;
            box-shadow: 0 -2px 6px rgba(0,0,0,0.05);
            z-index: 999;
        }
        .tab {
            flex: 1;
            text-align: center;
            color: #8d6e63;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            padding: 4px 0;
        }
        .tab.active {
            color: #4e342e;
            font-weight: 600;
        }
        .tab-icon {
            display: block;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>☕ Coffee-Ko Inventory</header>

        <input class="searchbar" type="text" placeholder="Search ingredients..." id="searchQuery" />

        <div class="category-container" id="categoryButtons">
            <button class="category-button active" data-category="All">All</button>
            <button class="category-button" data-category="Coffee">Coffee</button>
            <button class="category-button" data-category="Drinks">Drinks</button>
            <button class="category-button" data-category="Snacks">Snacks</button>
            <button class="category-button" data-category="Essentials">Essentials</button>
        </div>

        <div id="inventoryList"></div>

        <div style="text-align: center; margin-top: 24px;">
            <button class="button" onclick="addNewItem()">➕ Add New Item</button>
        </div>
    </div>

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
            item.stock = newStock;
            renderInventory();
        }

        async function deleteItem(id) {
            await fetch(`/inventories/${id}`, {
                method: "DELETE",
                headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content }
            });
            inventoryItems = inventoryItems.filter(i => i.id !== id);
            renderInventory();
        }

        async function addNewItem() {
            const name = prompt("Enter item name:");
            const stock = parseInt(prompt("Enter stock:"), 10);
            const category = prompt("Enter category:");
            const price = parseFloat(prompt("Enter price:"));

            if (name && !isNaN(stock) && category && !isNaN(price)) {
                const response = await fetch("/inventories", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ name, stock, category, price })
                });

                const newItem = await response.json();
                inventoryItems.push(newItem); // ✅ Add to local array
                renderInventory(); // ✅ Re-render
            }
        }

        fetchInventory();
    </script>
</body>
</html>
