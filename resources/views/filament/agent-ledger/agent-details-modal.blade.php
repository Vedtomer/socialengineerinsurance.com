<div class="agent-ledger-modal space-y-6 text-gray-900">
    <div class="agent-ledger-summary-grid">
        <div class="agent-ledger-card agent-ledger-card--info">
            <div class="flex items-start gap-2.5">
                <div class="agent-ledger-card__icon text-slate-700">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5a7.5 7.5 0 0115 0" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="agent-ledger-card__value break-words">{{ $summary->agent_code ?: '-' }}</div>
                    <div class="mt-1 break-words text-[0.82rem] leading-tight text-slate-600">{{ $summary->agent_mobile_number ?: '-' }}</div>
                </div>
            </div>
        </div>

        <div class="agent-ledger-card agent-ledger-card--credit">
            <div class="flex items-start gap-2.5">
                <div class="agent-ledger-card__icon">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 12h10" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 8l4 4-4 4" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="agent-ledger-card__value break-words">Rs. {{ number_format((float) $summary->total_credit, 2) }}</div>
                    <div class="agent-ledger-card__meta">Total credit amount</div>
                </div>
            </div>
        </div>

        <div class="agent-ledger-card agent-ledger-card--debit">
            <div class="flex items-start gap-2.5">
                <div class="agent-ledger-card__icon">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 12h10" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4 4-4" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="agent-ledger-card__value break-words">Rs. {{ number_format((float) $summary->total_debit, 2) }}</div>
                    <div class="agent-ledger-card__meta">Total debit amount</div>
                </div>
            </div>
        </div>

        <div class="agent-ledger-card agent-ledger-card--balance">
            <div class="flex items-start gap-2.5">
                <div class="agent-ledger-card__icon">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="agent-ledger-card__value break-words">Rs. {{ number_format((float) $summary->balance, 2) }}</div>
                    <div class="agent-ledger-card__meta">Balance</div>
                </div>
            </div>
        </div>
    </div>

    <div class="agent-ledger-table-wrap overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="w-full overflow-x-auto">
            <table class="agent-ledger-table w-full border-collapse text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="border border-slate-300 px-3 py-1.5 text-left text-[0.88rem] font-semibold leading-none text-slate-900">Date</th>
                        <th class="border border-slate-300 px-3 py-1.5 text-left text-[0.88rem] font-semibold leading-none text-slate-900">Particulars</th>
                        <th class="agent-ledger-col-debit-head border border-slate-300 bg-rose-50 px-3 py-1.5 text-right text-[0.88rem] font-semibold leading-none text-rose-800">Debit</th>
                        <th class="agent-ledger-col-credit-head border border-slate-300 bg-emerald-50 px-3 py-1.5 text-right text-[0.88rem] font-semibold leading-none text-emerald-800">Credit</th>
                        <th class="agent-ledger-col-balance-head border border-slate-300 bg-sky-50 px-3 py-1.5 text-right text-[0.88rem] font-semibold leading-none text-sky-800">Balance</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse ($entries as $entry)
                        @php
                            $particularParts = array_filter([
                                $entry->credit_ref ? 'Credit Ref: ' . $entry->credit_ref : null,
                                $entry->debit_ref ? 'Debit Ref: ' . $entry->debit_ref : null,
                                $entry->note ? 'Note: ' . $entry->note : null,
                            ]);
                            $particulars = count($particularParts) ? implode(' | ', $particularParts) : 'Ledger entry';
                            $runningBalance = number_format((float) ($entry->credit - $entry->debit), 2);
                        @endphp
                        <tr class="align-top">
                            <td class="whitespace-nowrap border border-slate-300 px-3 py-1 text-[0.85rem] leading-tight text-slate-900">
                                {{ optional($entry->entry_date)->format('d M Y') ?: '-' }}
                            </td>
                            <td class="max-w-[380px] border border-slate-300 px-3 py-1 text-[0.85rem] leading-tight text-slate-800">
                                <div class="break-words">{{ $particulars }}</div>
                            </td>
                            <td class="agent-ledger-col-debit whitespace-nowrap border border-slate-300 bg-rose-50/40 px-3 py-1 text-right text-[0.85rem] font-medium leading-tight text-rose-700">
                                {{ (float) $entry->debit > 0 ? number_format((float) $entry->debit, 2) : '-' }}
                            </td>
                            <td class="agent-ledger-col-credit whitespace-nowrap border border-slate-300 bg-emerald-50/40 px-3 py-1 text-right text-[0.85rem] font-medium leading-tight text-emerald-700">
                                {{ (float) $entry->credit > 0 ? number_format((float) $entry->credit, 2) : '-' }}
                            </td>
                            <td class="agent-ledger-col-balance whitespace-nowrap border border-slate-300 bg-sky-50/40 px-3 py-1 text-right text-[0.86rem] font-semibold leading-tight {{ (float) ($entry->credit - $entry->debit) < 0 ? 'is-negative text-rose-700' : 'is-positive text-sky-800' }}">
                                {{ $runningBalance }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-500">No ledger entries found for this agent.</td>
                        </tr>
                    @endforelse
                    @if ($entries->isNotEmpty())
                        <tr class="agent-ledger-total-row bg-slate-50">
                            <td colspan="2" class="border border-slate-300 px-3 py-1.5 text-right text-[0.88rem] font-semibold leading-none text-slate-900">TOTAL</td>
                            <td class="agent-ledger-col-debit whitespace-nowrap border border-slate-300 bg-rose-50 px-3 py-1.5 text-right text-[0.88rem] font-semibold leading-none text-rose-800">
                                {{ number_format((float) $entries->sum('debit'), 2) }}
                            </td>
                            <td class="agent-ledger-col-credit whitespace-nowrap border border-slate-300 bg-emerald-50 px-3 py-1.5 text-right text-[0.88rem] font-semibold leading-none text-emerald-800">
                                {{ number_format((float) $entries->sum('credit'), 2) }}
                            </td>
                            <td class="agent-ledger-col-balance whitespace-nowrap border border-slate-300 bg-sky-50 px-3 py-1.5 text-right text-[0.88rem] font-bold leading-none {{ (float) $summary->balance < 0 ? 'is-negative text-rose-700' : 'is-positive text-sky-800' }}">
                                {{ number_format((float) $summary->balance, 2) }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
