<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatsappMessageLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('whatsapp_message_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('mobile_number');
            $table->string('message_type')->nullable(); // 'no_policy' or 'daily_report'
            $table->integer('policy_count')->default(0);
            $table->decimal('total_commission', 10, 2)->nullable();
            $table->integer('days_since_last_policy')->nullable();
            
            $table->text('request_payload')->nullable();
            $table->text('response_body')->nullable();
            $table->boolean('is_successful')->default(false);
            $table->string('error_message')->nullable();
            
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('whatsapp_message_logs');
    }
}