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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->constrained('policies')->onDelete('cascade');
            $table->foreignId('agent_id')->constrained('commissions')->onDelete('cascade');
            $table->decimal('amount_paid', 10, 2);
            $table->decimal('amount_remaining', 10, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('payment_date');
            $table->string('status')->default('completed');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Add field to policies table to track total amount due from agent
        Schema::table('policies', function (Blueprint $table) {
            $table->decimal('agent_amount_due', 10, 2)->nullable()->after('agent_commission');
            $table->decimal('agent_amount_paid', 10, 2)->default(0)->after('agent_amount_due');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policies', function (Blueprint $table) {
            $table->dropColumn(['agent_amount_due', 'agent_amount_paid']);
        });
        
        Schema::dropIfExists('transactions');
    }
};
