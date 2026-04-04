<div class="agent-ledger-import-modal">
    <div class="agent-ledger-summary-grid">
        <div class="agent-ledger-card agent-ledger-card--info">
            <div class="agent-ledger-import-card__label">Date</div>
            <div class="agent-ledger-import-card__value">{{ optional($history->entry_date)->format('d M Y') ?: '-' }}</div>
        </div>
        <div class="agent-ledger-card agent-ledger-card--info">
            <div class="agent-ledger-import-card__label">Excel File</div>
            <div class="agent-ledger-import-card__value agent-ledger-import-card__value--wrap">{{ $history->file_name }}</div>
        </div>
        <div class="agent-ledger-card agent-ledger-card--info">
            <div class="agent-ledger-import-card__label">Total Records</div>
            <div class="agent-ledger-import-card__value">{{ $history->total_records }}</div>
        </div>
        <div class="agent-ledger-card agent-ledger-card--info">
            <div class="agent-ledger-import-card__label">Imported By</div>
            <div class="agent-ledger-import-card__value">{{ optional($history->importer)->name ?: '-' }}</div>
        </div>
    </div>

    <div class="agent-ledger-table-wrap agent-ledger-import-table-wrap">
        <div class="agent-ledger-import-table-scroll">
        <table class="agent-ledger-table agent-ledger-import-table">
            <colgroup>
                <col class="w-12">
                <col class="w-40">
                <col class="w-28">
                <col class="w-32">
                <col class="w-28">
                <col class="w-32">
                <col>
            </colgroup>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Agent Code</th>
                    <th class="agent-ledger-col-credit-head agent-ledger-align-right">Credit</th>
                    <th>Credit Ref</th>
                    <th class="agent-ledger-col-debit-head agent-ledger-align-right">Debit</th>
                    <th>Debit Ref</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($history->rows->sortBy('row_index') as $row)
                    <tr>
                        <td>{{ $row->row_index }}</td>
                        <td>{{ $row->agent_code ?: '-' }}</td>
                        <td class="agent-ledger-col-credit agent-ledger-align-right">{{ number_format((float) $row->credit, 2) }}</td>
                        <td>{{ $row->credit_ref ?: '-' }}</td>
                        <td class="agent-ledger-col-debit agent-ledger-align-right">{{ number_format((float) $row->debit, 2) }}</td>
                        <td>{{ $row->debit_ref ?: '-' }}</td>
                        <td class="agent-ledger-note-cell">{{ $row->note ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="agent-ledger-import-empty">No imported rows found for this file.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
