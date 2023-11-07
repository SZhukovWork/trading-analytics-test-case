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
        Schema::create('users', function (Blueprint $table) {
            $table->string("first_name",50);
            $table->string("last_name",50);
            $table->primary(["first_name","last_name"]);
            $table->string("email",50);
            $table->unsignedSmallInteger("age");
            $table->timestamp("update_time")->nullable()->default(null);
            $table->index("update_time");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
