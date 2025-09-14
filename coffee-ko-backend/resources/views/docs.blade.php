<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee-Ko API Docs</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen p-6 font-sans">

    <div class="max-w-5xl mx-auto bg-white shadow-lg rounded-2xl p-8">
        <h1 class="text-3xl font-extrabold text-yellow-800 mb-6">📖 Coffee-Ko API Documentation</h1>

        <p class="text-gray-600 mb-6">
            This is the API documentation for <strong>Coffee-Ko</strong>. Below are the available endpoints with their usage.
        </p>

        <div class="space-y-6">
            <div class="border rounded-lg p-4 bg-gray-50">
                <h2 class="font-bold text-lg mb-2">🔹 Inventory</h2>
                <ul class="list-disc list-inside text-gray-700">
                    <li><span class="font-mono bg-green-100 px-2 rounded">GET</span> /api/inventory → List items</li>
                    <li><span class="font-mono bg-blue-100 px-2 rounded">POST</span> /api/inventory → Add new item</li>
                    <li><span class="font-mono bg-yellow-100 px-2 rounded">PUT</span> /api/inventory/{id} → Update item</li>
                    <li><span class="font-mono bg-red-100 px-2 rounded">DELETE</span> /api/inventory/{id} → Delete item</li>
                </ul>
            </div>

            <div class="border rounded-lg p-4 bg-gray-50">
                <h2 class="font-bold text-lg mb-2">🔹 Sales</h2>
                <ul class="list-disc list-inside text-gray-700">
                    <li><span class="font-mono bg-green-100 px-2 rounded">GET</span> /api/sales → List sales records</li>
                    <li><span class="font-mono bg-blue-100 px-2 rounded">POST</span> /api/sales → Record new sale</li>
                </ul>
            </div>

            <div class="border rounded-lg p-4 bg-gray-50">
                <h2 class="font-bold text-lg mb-2">🔹 Reports</h2>
                <ul class="list-disc list-inside text-gray-700">
                    <li><span class="font-mono bg-green-100 px-2 rounded">GET</span> /api/reports → Generate report</li>
                </ul>
            </div>
        </div>

        <div class="mt-8">
            <a href="{{ url('/') }}"
               class="inline-block px-6 py-2 bg-yellow-600 text-white rounded-lg shadow hover:bg-yellow-700 transition">
               ⬅ Back to Home
            </a>
        </div>
    </div>

</body>
</html>