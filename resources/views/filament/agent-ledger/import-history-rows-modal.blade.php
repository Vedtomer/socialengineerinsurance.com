<div class="space-y-4">
    <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
            <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Date</div>
            <div class="mt-1 text-sm font-semibold text-slate-900">{{ optional($history->entry_date)->format('d M Y') ?: '-' }}</div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
            <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Excel File</div>
            <div class="mt-1 break-words text-sm font-semibold text-slate-900">{{ $history->file_name }}</div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
            <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Records</div>
            <div class="mt-1 text-sm font-semibold text-slate-900">{{ $history->total_records }}</div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
            <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Imported By</div>
            <div class="mt-1 text-sm font-semibold text-slate-900">{{ optional($history->importer)->name ?: '-' }}</div>
        </div>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">#</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Agent Code</th>
                    <th class="px-4 py-3 text-right font-semibold text-slate-700">Credit</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Credit Ref</th>
                    <th class="px-4 py-3 text-right font-semibold text-slate-700">Debit</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Debit Ref</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Note</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse ($history->rows->sortBy('row_index') as $row)
                    <tr>
                        <td class="px-4 py-3 text-slate-700">{{ $row->row_index }}</td>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $row->agent_code ?: '-' }}</td>
                        <td class="px-4 py-3 text-right text-emerald-700">{{ number_format((float) $row->credit, 2) }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $row->credit_ref ?: '-' }}</td>
                        <td class="px-4 py-3 text-right text-rose-700">{{ number_format((float) $row->debit, 2) }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $row->debit_ref ?: '-' }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $row->note ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-slate-500">No imported rows found for this file.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
