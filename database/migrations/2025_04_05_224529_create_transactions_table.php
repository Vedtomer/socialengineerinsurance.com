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
        if (! Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
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
        }

        // Add fields only when the legacy policies table exists.
        if (Schema::hasTable('policies')) {
            Schema::table('policies', function (Blueprint $table) {
                if (! Schema::hasColumn('policies', 'agent_amount_due')) {
                    $table->decimal('agent_amount_due', 10, 2)->nullable()->after('agent_commission');
                }

                if (! Schema::hasColumn('policies', 'agent_amount_paid')) {
                    $table->decimal('agent_amount_paid', 10, 2)->default(0)->after('agent_amount_due');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('policies')) {
            Schema::table('policies', function (Blueprint $table) {
                if (Schema::hasColumn('policies', 'agent_amount_due')) {
                    $table->dropColumn('agent_amount_due');
                }

                if (Schema::hasColumn('policies', 'agent_amount_paid')) {
                    $table->dropColumn('agent_amount_paid');
                }
            });
        }

        Schema::dropIfExists('transactions');
    }
};
