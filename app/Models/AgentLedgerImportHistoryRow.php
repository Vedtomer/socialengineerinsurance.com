<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentLedgerImportHistoryRow extends Model
{
    use HasFactory;

    protected $fillable = [
        'import_history_id',
        'row_index',
        'agent_code',
        'credit',
        'credit_ref',
        'debit',
        'debit_ref',
        'note',
        'ledger_entry_id',
    ];

    protected $casts = [
        'credit' => 'decimal:2',
        'debit' => 'decimal:2',
    ];

    public function importHistory()
    {
        return $this->belongsTo(AgentLedgerImportHistory::class, 'import_history_id');
    }

    public function ledgerEntry()
    {
        return $this->belongsTo(AgentLedgerEntry::class, 'ledger_entry_id');
    }
}
