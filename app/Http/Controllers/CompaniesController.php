<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Helpers\CurlRequest;
class CompaniesController extends Controller
{
    public function index(){
        return view('companies.all_companies');
    }

    public function company($slug){

        $url = env('COMPANIES_SERVER_IP').$slug;
        $curl = new CurlRequest();
        $response = $curl->companiesCurl($url);
        $response =json_decode($response);
        $data = $response->data;
        return view('companies.company')->with('json', $data);
    }

    public function people($slug){

        $url = env('PEOPLES_SERVER_IP').$slug;
        $curl = new CurlRequest();
        $response = $curl->companiesCurl($url);
        $response =json_decode($response);
        $data = $response->data;
        return view('companies.people')->with('json', $data);
    }
}
