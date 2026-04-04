<div class="agent-ledger-modal space-y-6 text-gray-900">
    <div class="agent-ledger-summary-grid">
        <div class="agent-ledger-card agent-ledger-card--info">
            <div class="agent-ledger-card__row">
                <div class="agent-ledger-card__icon text-slate-700">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 11a4 4 0 100-8 4 4 0 000 8z" />
                    </svg>
                </div>
                <div class="agent-ledger-card__content">
                    <div class="agent-ledger-card__value break-words">{{ $summary->agent_code ?: '-' }}</div>
                    <div class="mt-1 break-words text-[0.82rem] leading-tight text-slate-600">{{ $summary->agent_mobile_number ?: '-' }}</div>
                </div>
            </div>
        </div>

        <div class="agent-ledger-card agent-ledger-card--credit">
            <div class="agent-ledger-card__row">
                <div class="agent-ledger-card__icon">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 12l-7 7-7-7" />
                    </svg>
                </div>
                <div class="agent-ledger-card__content">
                    <div class="agent-ledger-card__value break-words">Rs. {{ number_format((float) $summary->total_credit, 2) }}</div>
                    <div class="agent-ledger-card__meta">Total credit amount</div>
                </div>
            </div>
        </div>

        <div class="agent-ledger-card agent-ledger-card--debit">
            <div class="agent-ledger-card__row">
                <div class="agent-ledger-card__icon">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19V5" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12l7-7 7 7" />
                    </svg>
                </div>
                <div class="agent-ledger-card__content">
                    <div class="agent-ledger-card__value break-words">Rs. {{ number_format((float) $summary->total_debit, 2) }}</div>
                    <div class="agent-ledger-card__meta">Total debit amount</div>
                </div>
            </div>
        </div>

        <div class="agent-ledger-card agent-ledger-card--balance">
            <div class="agent-ledger-card__row">
                <div class="agent-ledger-card__icon">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v10.5A2.25 2.25 0 0118.75 19.5H5.25A2.25 2.25 0 013 17.25V6.75z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.75h18" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25h3" />
                    </svg>
                </div>
                <div class="agent-ledger-card__content">
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
