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

            // Separate year and month columns
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');

            // Current month commission
            $table->decimal('total_commission', 15, 2)->default(0);

         

            $table->decimal('total_premium_due', 15, 2)->default(0);
            $table->decimal('amount_paid', 15, 2)->default(0);

            // Current month pending
            $table->decimal('pending_amount', 15, 2)->default(0);

        

            

            // Carry forward due (previous month pending)
            $table->decimal('carry_forward_due', 15, 2)->default(0);

            $table->decimal('final_amount_due', 15, 2)->default(0);

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Unique constraint to avoid duplicate settlements per agent per year/month
            $table->unique(['agent_id', 'year', 'month']);
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
