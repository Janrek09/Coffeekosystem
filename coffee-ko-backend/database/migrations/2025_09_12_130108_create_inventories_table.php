<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
        table->id();
        $table->string('name');
        $table->string('category');
        $table->unsignedInteger('stock')->default(0);
        $table->decimal('price', 8, 2)->default(0.00);
        $table->unsignedInteger('threshold')->default(0);
        $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
