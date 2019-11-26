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
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Validators\Failure;
use App\File_Failure;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
class VerifyEmailsImport implements ToModel, WithChunkReading, ShouldQueue, WithStartRow, WithLimit, WithValidation, SkipsOnFailure,  WithCustomCsvSettings
{
    use Importable;
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
    public function setExcludeHeader($exclude_header) 
    {     
        $this->exclude_header = $exclude_header;
    }
    public function rules(): array
    {
        $email = strval($this->email);
        
        return [
            $email => ['required', 'string', 'email', 'max:255'],
        ];

    }
    public function model(array $row)
    {
        $user_id=$this->user->id;
        $file_id=$this->file->id;
        $email = $this->email;

        $email_field=strtolower(Functions::removeAccentsEmail($row[$email]));
        $domain = explode('@', $email_field)[1];
        if ((strpos($domain, 'yahoo.')!== false) || (strpos($domain, 'aol.com')!== false) || (strpos($domain, 'ymail.com')!== false)) 
        {
            $server_output=array("type"=>"PersonalVerificationDomain",'status'=>"Catch All");
            $server_output=json_encode($server_output);

            return new Emails([
                'email' => $email_field,
                'status' => 'Unknown',
                'server_status' => '-',
                'user_id'=>$user_id,
                'user_file_id'=>$file_id,
                'type'=>'verify',
                'server_json_dump'=>$server_output,
            ]);
        }
        return new Emails([
            'email' => $email_field,
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
    /**
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures)
    {

        foreach ($failures as $failure) {
            $failure_db=new File_Failure();
            $failure_db->user_file_id=$this->file->id;
            $failure_db->row=$failure->row(); 
            if($failure->attribute()==$this->email)
            {
                $attribute="Email";
            }
            $failure_db->attribute=$attribute;
            $failure_db->errors=json_encode($failure->errors());
            $failure_db->values=json_encode($failure->values());
            $failure_db->save();
        }
    }
    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'ISO-8859-1'
        ];
    }
}