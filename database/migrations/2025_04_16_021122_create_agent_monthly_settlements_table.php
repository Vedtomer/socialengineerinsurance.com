<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentMonthlySettlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_monthly_settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users');
            $table->date('settlement_month');
            $table->decimal('total_commission', 15, 2)->default(0);
            $table->decimal('total_premium_due', 15, 2)->default(0);
            $table->decimal('pay_later_amount', 15, 2)->default(0);
            $table->decimal('pay_later_with_adjustment_amount', 15, 2)->default(0);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('pending_amount', 15, 2)->default(0);
            $table->decimal('previous_month_commission', 15, 2)->default(0);
            $table->decimal('adjusted_commission', 15, 2)->default(0);
            $table->decimal('carry_forward_due', 15, 2)->default(0); // Added this field
            $table->decimal('final_amount_due', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Unique constraint to ensure only one record per agent per month
            $table->unique(['agent_id', 'settlement_month']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_monthly_settlements');
    }
}