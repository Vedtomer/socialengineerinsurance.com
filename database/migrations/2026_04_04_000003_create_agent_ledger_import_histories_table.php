<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_ledger_import_histories', function (Blueprint $table) {
            $table->id();
            $table->date('entry_date');
            $table->string('file_name');
            $table->string('file_path')->nullable();
            $table->unsignedInteger('total_records')->default(0);
            $table->foreignId('imported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('agent_ledger_import_history_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_history_id')->constrained('agent_ledger_import_histories')->cascadeOnDelete();
            $table->unsignedInteger('row_index')->default(0);
            $table->string('agent_code', 50)->nullable();
            $table->decimal('credit', 12, 2)->default(0);
            $table->string('credit_ref')->nullable();
            $table->decimal('debit', 12, 2)->default(0);
            $table->string('debit_ref')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('ledger_entry_id')->nullable()->constrained('agent_ledger_entries')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_ledger_import_history_rows');
        Schema::dropIfExists('agent_ledger_import_histories');
    }
};
