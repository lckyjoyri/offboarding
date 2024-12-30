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
        Schema::create('clearance', function (Blueprint $table) {
            $table->increments('id'); 
            $table->unsignedBigInteger('employment_type');
            $table->string('statement', 255); 
            $table->timestamps(); 
    
            $table->foreign('employment_type')->references('id')->on('employment_types')->onDelete('cascade'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clearances');
    }
};
