<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use App\Models\Agent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
class AgentExport implements FromCollection, WithHeadings

{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            // 'id',
            'Agent Name',
            'Policy',
            'Premium',
            'Earn Points',
            'Email',
            'City',
            'Mobile',
        ];
    }


}
