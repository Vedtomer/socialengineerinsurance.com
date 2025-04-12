<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentCodesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agent_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('insurance_product_id')->nullable();
            $table->enum('commission_type', ['fixed', 'percentage']);
            $table->decimal('commission', 10, 2);
            $table->string('code', 6)->unique()->nullable();
            $table->enum('payment_type', ['agent_full_payment', 'commission_deducted', 'pay_later_with_adjustment', 'pay_later']);
            $table->decimal('gst', 5, 2)->default(15.25);
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('payout', 10, 2)->nullable();
            $table->unsignedInteger('insurance_company_id')->nullable(); // Changed to unsignedInteger to match companies.id
            $table->boolean('commission_settlement')->default(false);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('insurance_product_id')->references('id')->on('insurance_products')->onDelete('cascade');
            $table->foreign('insurance_company_id')->references('id')->on('insurance_companies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_codes');
    }
}