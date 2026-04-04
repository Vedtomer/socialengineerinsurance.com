<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentLedgerEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_date',
        'agent_code',
        'agent_code_id',
        'user_id',
        'credit',
        'credit_ref',
        'debit',
        'debit_ref',
        'note',
        'imported_by',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'credit' => 'decimal:2',
        'debit' => 'decimal:2',
    ];

    public function agentCode()
    {
        return $this->belongsTo(AgentCode::class, 'agent_code_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function importer()
    {
        return $this->belongsTo(User::class, 'imported_by');
    }

    public function importHistoryRows()
    {
        return $this->hasMany(AgentLedgerImportHistoryRow::class, 'ledger_entry_id');
    }
}
