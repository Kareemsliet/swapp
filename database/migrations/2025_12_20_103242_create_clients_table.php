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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email")->nullable()->unique();
            $table->string("phone")->nullable()->unique();
            $table->date("birthdate")->nullable();
            $table->longText("summary")->nullable();
            $table->timestamp("email_verified_at")->nullable();
            $table->timestamp("phone_verified_at")->nullable();
            $table->text("verified_meta")->nullable();
            $table->string("password");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
