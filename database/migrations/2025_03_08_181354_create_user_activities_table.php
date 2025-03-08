<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('user_type')->nullable(); // agent, customer, etc.
            $table->string('method'); // GET, POST, etc.
            $table->string('route');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('request_data')->nullable();
            $table->integer('response_code')->nullable();
            $table->longText('response_data')->nullable();
            $table->timestamps();
            
            // Foreign key relationship
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Add index for faster queries
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_activities');
    }
}