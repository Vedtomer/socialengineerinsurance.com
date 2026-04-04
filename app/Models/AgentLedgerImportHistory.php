<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentLedgerImportHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_date',
        'file_name',
        'file_path',
        'total_records',
        'imported_by',
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];

    public function importer()
    {
        return $this->belongsTo(User::class, 'imported_by');
    }

    public function rows()
    {
        return $this->hasMany(AgentLedgerImportHistoryRow::class, 'import_history_id');
    }
}
