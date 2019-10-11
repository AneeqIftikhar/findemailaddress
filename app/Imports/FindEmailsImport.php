<?php

namespace App\Imports;

use App\Emails;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterImport;
use App\User;
use App\UserFiles;
class FindEmailsImport implements ToModel, WithChunkReading, ShouldQueue
{
    protected $_user = null;
    protected $_file = null;
    public function setUser($user) 
    {     
        $this->_user = $user;
    }
    public function setUserFile($file) 
    {     
        $this->_file = $file;
    }
    public function model(array $row)
    {
        $user_id=$this->_user->id;
        $file_id=$this->_file->id;
        return new Emails([
            'first_name' => $row[0],
            'last_name' => $row[1],
            'domain' => $row[2],
            'status' => 'Unverified',
            'user_id'=>$user_id,
            'user_file_id'=>$file_id,
            'type'=>'find',
        ]);
    }
    
    public function chunkSize(): int
    {
        return 1500;
    }
    
}