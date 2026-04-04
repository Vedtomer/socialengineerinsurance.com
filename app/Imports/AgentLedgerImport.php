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
            'credit_ref' => $this->normalizeText($row['credit_ref'] ?? null),
            'debit' => $this->normalizeAmount($row['debit'] ?? 0),
            'debit_ref' => $this->normalizeText($row['debit_ref'] ?? null),
            'note' => $this->normalizeText($row['note'] ?? null),
            'imported_by' => Auth::id(),
        ]);
    }

    public function rules(): array
    {
        return [
            'agent_code' => ['required', 'string'],
            'credit' => ['nullable', 'numeric', 'min:0'],
            'credit_ref' => ['nullable', 'string', 'max:255'],
            'debit' => ['nullable', 'numeric', 'min:0'],
            'debit_ref' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'agent_code.required' => 'Agent code is required.',
            'credit.numeric' => 'Credit must be numeric.',
            'credit_ref.string' => 'Credit reference must be text.',
            'debit.numeric' => 'Debit must be numeric.',
            'debit_ref.string' => 'Debit reference must be text.',
            'note.string' => 'Note must be text.',
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

    protected function normalizeText($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
