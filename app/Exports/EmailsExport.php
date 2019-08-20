<?php

namespace App\Exports;

use App\Emails;
use Maatwebsite\Excel\Concerns\FromCollection;
use Auth;
class EmailsExport implements FromCollection
{

	public $user_file_id;
    public $records;
    public $source;
    public $type; //find or verify
	public function set_details($id,$records,$source,$type)
	{
		$this->user_file_id=$id;
        $this->records=$records;
        $this->source=$source;
        $this->type=$type;
	}
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if($this->source=='file')
        {

            if($this->type=='find')
            {
               if($this->records=='all')
                {
                    return Emails::select('first_name','last_name','domain','email','status')->where('user_file_id', $this->user_file_id)->get();
                }
                else
                {
                    return Emails::select('first_name','last_name','domain','email','status')->where('user_file_id', $this->user_file_id)->where('status', 'Valid')->get();
                }
            }
            else if($this->type=='verify')
            {
                if($this->records=='all')
                {
                    return Emails::select('email','status')->where('user_file_id', $this->user_file_id)->get();
                }
                else
                {
                    return Emails::select('email','status')->where('user_file_id', $this->user_file_id)->where('status', 'Valid')->get();
                }
            }

            
        }
        else
        {
            $user=Auth::user();
            if($this->type=='find')
            {
                
                if($this->records=='all')
                {
                    return Emails::select('first_name','last_name','domain','email','status')->where('user_id', $user->id)->where('type', 'find')->get();
                }
                else
                {
                    return Emails::select('first_name','last_name','domain','email','status')->where('user_id', $user->id)->where('type', 'find')->where('status', 'Valid')->get();
                }
            }
            else if($this->type=='verify')
            {
                if($this->records=='all')
                {
                    return Emails::select('email','status')->where('user_id', $user->id)->where('type', 'verify')->get();
                }
                else
                {
                    return Emails::select('email','status')->where('user_id', $user->id)->where('type', 'verify')->where('status', 'Valid')->get();
                }
            }
        }
        
        
        
    }
}
