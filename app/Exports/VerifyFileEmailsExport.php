<?php

namespace App\Exports;

use App\Emails;
use Maatwebsite\Excel\Concerns\FromCollection;
use Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
class FoundFileEmailsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{

    public $records;
    public $id;
	public function set_details($id,$records)
	{
        $this->records=$records;
        $this->id=$id;
	}
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user=Auth::user();
        if($this->records=='all')
        {
            return Emails::select('email','status','server_status','created_at')->where('user_file_id', $this->id)->get();
        }
        else
        {
            return Emails::select('email','status','server_status','created_at')->where('user_file_id', $this->id)->where('status', 'Valid')->get();
        }
 
    }
    public function headings(): array
    {
        return [
            "Email", "Server Status" ,"Status", "Date/Time"
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $sheet=$event->sheet;
                $cellRange = 'A1:Z1'; // All headers
                $sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                $sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
                
            },
        ];
    }
}
