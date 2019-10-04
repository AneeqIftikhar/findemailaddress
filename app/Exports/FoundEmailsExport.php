<?php

namespace App\Exports;

use App\Emails;
use Maatwebsite\Excel\Concerns\FromCollection;
use Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
class FoundEmailsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{

    public $records;
	public function set_details($records)
	{
        $this->records=$records;
	}
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user=Auth::user();
        if($this->records=='all')
        {
            return Emails::select('first_name','last_name','domain','email','status')->where('user_id', $user->id)->where('type', 'find')->get();
        }
        else
        {
            return Emails::select('first_name','last_name','domain','email','status')->where('user_id', $user->id)->where('type', 'find')->where('status', 'Valid')->get();
        }
 
    }
    public function headings(): array
    {
        return [
            "Firstname", "Lastname", "Domain", "Email", "Status"
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:Z1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }
}
