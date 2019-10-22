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
use App\Package;
class FindEmailsImport implements ToModel, WithChunkReading, ShouldQueue, WithStartRow
{
    protected $user = null;
    protected $file = null;
    protected $first_name = 0;
    protected $last_name = 1;
    protected $domain = 2;
    protected $exclude_header=false;
    protected $limit=10;
    public function setUser($user) 
    {     
        $this->user = $user;
        $package=Package::where('id',$this->user->package_id)->first();
        if($this->user->credits >= ($package->credits*0.1))
        {
            $this->limit=(int) ($package->credits*0.1);
        }
        else
        {
            $this->limit=(int) ($this->user->credits);
        }
        
    }
    public function setUserFile($file) 
    {     
        $this->file = $file;
    }
    public function setHeaderMappings($first_name,$last_name,$domain) 
    {     
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->domain = $domain;
    }
    public function setExcludeHeader($exclude_header) 
    {     
        $this->exclude_header = $exclude_header;
    }
    public function model(array $row)
    {
        $user_id=$this->user->id;
        $file_id=$this->file->id;
        $first_name = $this->first_name;
        $last_name = $this->last_name;
        $domain = $this->domain;
        return new Emails([
            'first_name' => $row[$first_name],
            'last_name' => $row[$last_name],
            'domain' => $row[$domain],
            'status' => 'Unverified',
            'user_id'=>$user_id,
            'user_file_id'=>$file_id,
            'type'=>'find',
        ]);
    }
    
    public function chunkSize(): int
    {
        return 1000;
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