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
        Schema::create('tenant_apps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenants_id');
            $table->foreign('tenants_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->uuid('apps_id');
            $table->foreign('apps_id')->references('id')->on('apps')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_apps');
    }
};
