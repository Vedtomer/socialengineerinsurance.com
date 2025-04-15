<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->integer('month');
            $table->integer('year');
            $table->decimal('total_premium', 12, 2)->default(0);
            $table->decimal('total_commission', 12, 2)->default(0);
            $table->decimal('total_gst', 12, 2)->default(0); 
            $table->decimal('total_net_amount', 12, 2)->default(0);
            $table->decimal('total_agent_amount_due', 12, 2)->default(0);
            $table->integer('policies_count')->default(0);
            $table->string('payment_reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Add a unique constraint for agent, month, and year
            $table->unique(['agent_id', 'month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monthly_commissions');
    }
}