<?php

namespace App;

use App\Emails;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class EmailsImport implements ToModel, WithChunkReading, ShouldQueue
{
    protected $_user_id = null;
    protected $_file_id = null;
    public function setUserId($user_id) 
    {     
        $this->_user_id = $user_id;
    }
    public function setUserFileId($file_id) 
    {     
        $this->_file_id = $file_id;
    }
    public function model(array $row)
    {
        $user_id=$this->_user_id;
        $file_id=$this->_file_id;
        return new Emails([
            'first_name' => $row[0],
            'last_name' => $row[1],
            'domain' => $row[2],
            'status' => 'unverified',
            'user_id'=>$user_id,
            'user_file_id'=>$file_id,
        ]);
    }
    
    public function chunkSize(): int
    {
        return 100;
    }
}