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
        Schema::create('clearance_approvals', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('request_id'); 
            $table->unsignedInteger('seqno');
            $table->unsignedInteger('clearing_official_id'); 
            $table->string('comment', 255)->nullable(); 
            $table->tinyInteger('isApproved')->default(0); 
            $table->timestamps(); 
    
            // Foreign key constraints
            $table->foreign('request_id')->references('id')->on('clearance_requests')->onDelete('cascade');
            $table->foreign('clearing_official_id')->references('id')->on('sub_roles')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clearance_approvals');
    }
};
