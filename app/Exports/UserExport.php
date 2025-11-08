<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    private $users;
    public function __construct($users)
    {
        $this->users = $users;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $exportData = [];
        foreach ($this->users as $key => $value) {
            $exportData[] = [
                $value?->id ?? '-',
                $value?->name ?? '-',
                $value?->phone ?? '-',
                $value?->email ?? '-'
            ];
        }
        return collect($exportData);
    }

    public function headings(): array{
        return [
            'ID',
            'Name',
            'Mobile No.',
            'Email'
        ];
    }
}
