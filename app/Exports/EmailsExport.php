<?php

namespace App\Exports;

use App\Emails;
use Maatwebsite\Excel\Concerns\FromCollection;

class EmailsExport implements FromCollection
{

	public $user_file_id;
    public $records;
	public function set_details($id,$records)
	{
		$this->user_file_id=$id;
        $this->records=$records;
	}
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if($this->records=='all')
        {
            return Emails::select('first_name','last_name','domain','email','status')->where('user_file_id', $this->user_file_id)->get();
        }
        else
        {
            return Emails::select('first_name','last_name','domain','email','status')->where('user_file_id', $this->user_file_id)->where('status', 'valid')->get();
        }
        
    }
}
