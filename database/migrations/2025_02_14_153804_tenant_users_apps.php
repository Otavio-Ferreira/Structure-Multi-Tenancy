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
        Schema::create('tenant_users_apps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_apps_id');
            $table->foreign('tenant_apps_id')->references('id')->on('tenant_apps')->onDelete('cascade');
            $table->uuid('tenant_user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
