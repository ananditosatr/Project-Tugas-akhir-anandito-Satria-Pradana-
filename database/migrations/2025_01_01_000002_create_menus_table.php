<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->mediumText('image_base64')->nullable();
            $table->integer('stock')->default(0);
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->index('category_id');
            $table->index('is_available');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
