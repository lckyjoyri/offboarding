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
        Schema::create('clearance_official', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('clearance_id'); 
            $table->unsignedInteger('seqno'); 
            $table->string('title', 255); 
            $table->unsignedInteger('clearing_official'); 
            $table->timestamps(); 
    
            $table->foreign('clearance_id')->references('id')->on('clearance')->onDelete('cascade'); 
            $table->foreign('clearing_official')->references('id')->on('sub_roles')->onDelete('cascade'); 
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
