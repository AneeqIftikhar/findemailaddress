<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Helpers\CurlRequest;
use Auth;

class CompaniesController extends Controller
{
    public function index()
    {
        return view('companies.all_companies');
    }

    public function company($slug)
    {

        $url = env('COMPANIES_SERVER_IP') . $slug;
        $curl = new CurlRequest();
        $response = $curl->companiesCurl($url);
        $response = json_decode($response);
        $data = $response->data;
        $staffCount = $data->staff_count;
        $precision = 1;

        if ($staffCount != null) {
            if ($staffCount < 900) {
                // 0 - 900
                $n_format = number_format($staffCount, $precision);
                $suffix = '';
            } else if ($staffCount < 900000) {
                // 0.9k-850k
                $n_format = number_format($staffCount / 1000, $precision);
                $suffix = 'K';
            } else if ($staffCount < 900000000) {
                // 0.9m-850m
                $n_format = number_format($staffCount / 1000000, $precision);
                $suffix = 'M';
            } else if ($staffCount < 900000000000) {
                // 0.9b-850b
                $n_format = number_format($staffCount / 1000000000, $precision);
                $suffix = 'B';
            } else {
                // 0.9t+
                $n_format = number_format($staffCount / 1000000000000, $precision);
                $suffix = 'T';
            }
        } else {
            $n_format = "";
            $suffix = "";
        }
        $staffCountShort = $n_format . $suffix;
        return view('companies.company')->with('json', $data)->with('staffCount', $staffCountShort);
    }

    public function people($slug)
    {

        $url = env('PEOPLES_SERVER_IP') . $slug;
        $curl = new CurlRequest();
        $response = $curl->companiesCurl($url);
        $response = json_decode($response);
        $data = $response->data;
        return view('companies.people')->with('json', $data);
    }

    public function getEmail(Request $request)
    {
        $data = [];
        $slug = $request->slug;

        $user = Auth::user();
        if ($user->credits > 0) {
            $url = env('GET_PEOPLES_EMAIL_SERVER_IP') . $slug;
            $curl = new CurlRequest();
            $response = $curl->companiesCurl($url);
            $response = json_decode($response);
            $data['email'] = $response->data->email;
            $data['email_status'] = $response->data->email_status;
            $data['credits'] = $user->credits;
            $data['credits_left'] = $user->credits-1;
            $data['email_found'] = 'yes';

            if ($response->data->email_status == 'VALID') {
                $user->decrement('credits');
            }
        } else {
            $data['credits'] = $user->credits;
            $data['email_found'] = 'no';
        }
        $data = json_encode($data);
        return $data;
    }
}
