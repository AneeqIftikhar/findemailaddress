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
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Rules\BlackListDomains;
use App\Rules\IsValidDomain;
use App\Helpers\Functions;
class FindEmailsImport implements ToModel, WithChunkReading, ShouldQueue, WithStartRow, WithLimit, WithValidation
{
    protected $user = null;
    protected $file = null;
    protected $first_name = 0;
    protected $last_name = 1;
    protected $domain = 2;
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
    public function rules(): array
    {
        $first_name = $this->first_name;
        $last_name = $this->last_name;
        $domain = $this->domain;
        return [
            $first_name => ['required', 'string', 'max:50'],
            $last_name => ['required', 'string', 'max:50'],
            $domain => ['required', 'string', 'max:50', new BlackListDomains,new IsValidDomain]
        ];

    }
    public function model(array $row)
    {
        $user_id=$this->user->id;
        $file_id=$this->file->id;
        $first_name = $this->first_name;
        $last_name = $this->last_name;
        $domain = $this->domain;

        if (!isset($row[$first_name]) || !isset($row[$last_name]) || !isset($row[$domain])) {
            return null;
        }
        $first_name_field=strtolower(Functions::removeAccents($row[$first_name]));
        $last_name_field=strtolower(Functions::removeAccents($row[$last_name]));
        $domain_field=Functions::get_domain(strtolower(Functions::removeAccentsDomain($row[$domain])));
        return new Emails([
            'first_name' => $first_name_field,
            'last_name' => $last_name_field,
            'domain' => $domain_field,
            'status' => 'Unverified',
            'user_id'=>$user_id,
            'user_file_id'=>$file_id,
            'type'=>'find',
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