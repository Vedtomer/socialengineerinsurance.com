<?php

namespace App\Imports;

use App\Models\AgentCode;
use App\Models\AgentLedgerEntry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AgentLedgerImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    protected string $entryDate;

    public function __construct(string $entryDate)
    {
        $this->entryDate = $entryDate;
    }

    public function model(array $row)
    {
        $agentCode = AgentCode::with('agnet')->where('code', trim((string) $row['agent_code']))->first();

        if (! $agentCode) {
            throw ValidationException::withMessages([
                'agent_code' => "Agent code '{$row['agent_code']}' does not exist.",
            ]);
        }

        return new AgentLedgerEntry([
            'entry_date' => $this->entryDate,
            'agent_code' => $agentCode->code,
            'agent_code_id' => $agentCode->id,
            'user_id' => $agentCode->user_id,
            'credit' => $this->normalizeAmount($row['credit'] ?? 0),
            'debit' => $this->normalizeAmount($row['debit'] ?? 0),
            'imported_by' => Auth::id(),
        ]);
    }

    public function rules(): array
    {
        return [
            'agent_code' => ['required', 'string'],
            'credit' => ['nullable', 'numeric', 'min:0'],
            'debit' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'agent_code.required' => 'Agent code is required.',
            'credit.numeric' => 'Credit must be numeric.',
            'debit.numeric' => 'Debit must be numeric.',
            'credit.min' => 'Credit cannot be negative.',
            'debit.min' => 'Debit cannot be negative.',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    protected function normalizeAmount($value): float
    {
        if ($value === null || $value === '') {
            return 0;
        }

        return (float) $value;
    }
}
