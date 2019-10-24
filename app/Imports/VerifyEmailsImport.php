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
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithLimit;
class VerifyEmailsImport implements ToModel, WithChunkReading, ShouldQueue, WithStartRow, WithLimit
{
    protected $user = null;
    protected $file = null;
    protected $email = 0;
    protected $exclude_header=false;
    protected $limit=10;
    protected $chunk_size=1;
    public function setUser($user) 
    {     
        $this->user = $user;
    }
    public function setLimit($limit) 
    {     
        $this->limit=$limit;        
    }
    public function setChunkSize($chunk_size) 
    {     
        $this->chunk_size=$chunk_size;
        
    }
    public function setUserFile($file) 
    {     
        $this->file = $file;
    }
    public function setHeaderMappings($email) 
    {     
        $this->email = $email;
    }
    public function model(array $row)
    {
        $user_id=$this->user->id;
        $file_id=$this->file->id;
        $email = $this->email;
        return new Emails([
            'email' => $row[$email],
            'status' => 'Unverified',
            'user_id'=>$user_id,
            'user_file_id'=>$file_id,
            'type'=>'verify',
        ]);
    }
    
    public function chunkSize(): int
    {
        return $this->chunk_size;
    }
    public function startRow(): int
    {
        if($this->exclude_header)
        {
            return 2;
        }
        return 1;
    }
    public function limit(): int
    {
        return $this->limit;
    }
    
}