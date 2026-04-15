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
        Schema::create('campers', function (Blueprint $table) {
            $table->id();

            $table->string("name");
            $table->string("slug")->unique();
            $table->text("description");

            $table->string("image_path");
            $table->json("images")->nullable();

            $table->integer("seats")->default(4);
            $table->integer("beds")->default(4);
            $table->boolean("is_active")->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campers');
    }
};
