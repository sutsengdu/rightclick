<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('outcomes', function (Blueprint $table) {
            // id: bigint(20) unsigned, primary, auto_increment
            $table->id();

            // description: varchar(255) utf8mb4_unicode_ci, NOT NULL
            $table->string('description', 255);

            // price: decimal(8,2), NOT NULL
            $table->decimal('price', 8, 2);

            // created_at / updated_at: timestamp, nullable
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outcomes');
    }
};
