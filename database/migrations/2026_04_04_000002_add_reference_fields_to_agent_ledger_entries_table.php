<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agent_ledger_entries', function (Blueprint $table) {
            if (! Schema::hasColumn('agent_ledger_entries', 'credit_ref')) {
                $table->string('credit_ref')->nullable()->after('credit');
            }

            if (! Schema::hasColumn('agent_ledger_entries', 'debit_ref')) {
                $table->string('debit_ref')->nullable()->after('debit');
            }

            if (! Schema::hasColumn('agent_ledger_entries', 'note')) {
                $table->text('note')->nullable()->after('debit_ref');
            }
        });
    }

    public function down(): void
    {
        Schema::table('agent_ledger_entries', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('agent_ledger_entries', 'credit_ref') ? 'credit_ref' : null,
                Schema::hasColumn('agent_ledger_entries', 'debit_ref') ? 'debit_ref' : null,
                Schema::hasColumn('agent_ledger_entries', 'note') ? 'note' : null,
            ]);

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
