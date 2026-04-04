<div class="space-y-6 text-gray-900">
    <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-4 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5a7.5 7.5 0 0115 0" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="break-words text-sm font-semibold text-slate-900">{{ $summary->agent_code ?: '-' }}</div>
                    <div class="mt-2 break-words text-xs text-slate-600">{{ $summary->agent_mobile_number ?: '-' }}</div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-emerald-300 bg-gradient-to-br from-emerald-50 to-emerald-100 p-4 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-emerald-200 bg-white text-emerald-700 shadow-sm">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 12h10" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 8l4 4-4 4" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="break-words text-sm font-bold text-emerald-900">Rs. {{ number_format((float) $summary->total_credit, 2) }}</div>
                    <div class="mt-2 text-xs font-semibold text-emerald-700">Total credit amount</div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-rose-300 bg-gradient-to-br from-rose-50 to-rose-100 p-4 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-rose-200 bg-white text-rose-700 shadow-sm">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 12h10" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4 4-4" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="break-words text-sm font-bold text-rose-900">Rs. {{ number_format((float) $summary->total_debit, 2) }}</div>
                    <div class="mt-2 text-xs font-semibold text-rose-700">Total debit amount</div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-sky-300 bg-gradient-to-br from-sky-50 to-sky-100 p-4 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-sky-200 bg-white text-sky-700 shadow-sm">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="break-words text-sm font-bold text-sky-900">Rs. {{ number_format((float) $summary->balance, 2) }}</div>
                    <div class="mt-2 text-xs font-semibold text-sky-700">Balance</div>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="block space-y-4 p-4 md:hidden">
            @forelse ($entries as $entry)
                <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-3 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Entry Date</div>
                            <div class="mt-1 text-sm font-semibold text-slate-900">
                                {{ optional($entry->entry_date)->format('d M Y') ?: '-' }}
                            </div>
                        </div>
                        <div class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-600 shadow-sm">
                            {{ optional($entry->importer)->name ?: 'System' }}
                        </div>
                    </div>

                    <div class="mt-3 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-xl bg-white p-3">
                            <div class="text-xs font-semibold uppercase tracking-[0.16em] text-emerald-700">Credit</div>
                            <div class="mt-1 font-semibold text-slate-900">Rs. {{ number_format((float) $entry->credit, 2) }}</div>
                            <div class="mt-2 break-words text-xs text-slate-500">Ref: {{ $entry->credit_ref ?: '-' }}</div>
                        </div>
                        <div class="rounded-xl bg-white p-3">
                            <div class="text-xs font-semibold uppercase tracking-[0.16em] text-rose-700">Debit</div>
                            <div class="mt-1 font-semibold text-slate-900">Rs. {{ number_format((float) $entry->debit, 2) }}</div>
                            <div class="mt-2 break-words text-xs text-slate-500">Ref: {{ $entry->debit_ref ?: '-' }}</div>
                        </div>
                    </div>

                    <div class="mt-3 rounded-xl bg-white p-3">
                        <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Note</div>
                        <div class="mt-1 break-words text-sm leading-6 text-slate-700">{{ $entry->note ?: '-' }}</div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">
                    No ledger entries found for this agent.
                </div>
            @endforelse
        </div>

        <div class="hidden w-full overflow-x-auto md:block">
            <table class="w-full border-collapse text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="border border-slate-300 px-3 py-1.5 text-left text-[0.88rem] font-semibold leading-none text-slate-900">Date</th>
                        <th class="border border-slate-300 px-3 py-1.5 text-left text-[0.88rem] font-semibold leading-none text-slate-900">Particulars</th>
                        <th class="border border-slate-300 bg-rose-50 px-3 py-1.5 text-right text-[0.88rem] font-semibold leading-none text-rose-800">Debit</th>
                        <th class="border border-slate-300 bg-emerald-50 px-3 py-1.5 text-right text-[0.88rem] font-semibold leading-none text-emerald-800">Credit</th>
                        <th class="border border-slate-300 bg-sky-50 px-3 py-1.5 text-right text-[0.88rem] font-semibold leading-none text-sky-800">Balance</th>
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
                            <td class="whitespace-nowrap border border-slate-300 bg-rose-50/40 px-3 py-1 text-right text-[0.85rem] font-medium leading-tight text-rose-700">
                                {{ (float) $entry->debit > 0 ? number_format((float) $entry->debit, 2) : '-' }}
                            </td>
                            <td class="whitespace-nowrap border border-slate-300 bg-emerald-50/40 px-3 py-1 text-right text-[0.85rem] font-medium leading-tight text-emerald-700">
                                {{ (float) $entry->credit > 0 ? number_format((float) $entry->credit, 2) : '-' }}
                            </td>
                            <td class="whitespace-nowrap border border-slate-300 bg-sky-50/40 px-3 py-1 text-right text-[0.86rem] font-semibold leading-tight {{ (float) ($entry->credit - $entry->debit) < 0 ? 'text-rose-700' : 'text-sky-800' }}">
                                {{ $runningBalance }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-500">No ledger entries found for this agent.</td>
                        </tr>
                    @endforelse
                    @if ($entries->isNotEmpty())
                        <tr class="bg-slate-50">
                            <td colspan="2" class="border border-slate-300 px-3 py-1.5 text-right text-[0.88rem] font-semibold leading-none text-slate-900">TOTAL</td>
                            <td class="whitespace-nowrap border border-slate-300 bg-rose-50 px-3 py-1.5 text-right text-[0.88rem] font-semibold leading-none text-rose-800">
                                {{ number_format((float) $entries->sum('debit'), 2) }}
                            </td>
                            <td class="whitespace-nowrap border border-slate-300 bg-emerald-50 px-3 py-1.5 text-right text-[0.88rem] font-semibold leading-none text-emerald-800">
                                {{ number_format((float) $entries->sum('credit'), 2) }}
                            </td>
                            <td class="whitespace-nowrap border border-slate-300 bg-sky-50 px-3 py-1.5 text-right text-[0.88rem] font-bold leading-none {{ (float) $summary->balance < 0 ? 'text-rose-700' : 'text-sky-800' }}">
                                {{ number_format((float) $summary->balance, 2) }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
