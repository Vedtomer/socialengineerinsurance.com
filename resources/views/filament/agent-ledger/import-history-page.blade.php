<x-filament-panels::page>
    @if ($this->hasImportHistoryTable())
        {{ $this->table }}
    @else
        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-5 text-sm text-amber-900">
            Import history tables are not available yet. Run <code>php artisan migrate</code> on the server, then reopen this page.
        </div>
    @endif
</x-filament-panels::page>
