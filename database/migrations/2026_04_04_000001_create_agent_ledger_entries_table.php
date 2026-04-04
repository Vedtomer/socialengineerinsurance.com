<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->date('entry_date');
            $table->string('agent_code', 50);
            $table->foreignId('agent_code_id')->nullable()->constrained('agent_codes')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('credit', 12, 2)->default(0);
            $table->string('credit_ref')->nullable();
            $table->decimal('debit', 12, 2)->default(0);
            $table->string('debit_ref')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('imported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['entry_date', 'agent_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_ledger_entries');
    }
};
