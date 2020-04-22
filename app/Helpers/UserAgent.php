<?php

namespace App\Helpers;
use Jenssegers\Agent\Agent;
use Victorybiz\GeoIPLocation\GeoIPLocation;

class UserAgent
{
	public static function get_user_agent($ip)
	{
		$agent = new Agent();
		$browser = $agent->browser();// Chrome, IE, Safari, Firefox, ...
        $browser_version = $agent->version($browser);
        $platform = $agent->platform();// Ubuntu, Windows, OS X, ...
        $platform_version = $agent->version($platform);
        $device=$agent->device();
        $user_agent['ip']=$ip;
        $user_agent['browser']=$browser;
        $user_agent['browser_version']=$browser_version;
        $user_agent['platform']=$platform;
        $user_agent['platform_version']=$platform_version;
        $user_agent['device']=$device;
        $geoip = new GeoIPLocation(); 
        $geoip->setIP($ip);
        $user_agent['city']=$geoip->getCity();
        $user_agent['region']=$geoip->getRegion();
        $user_agent['country']=$geoip->getCountry();
        $user_agent['country_code']=$geoip->getCountryCode();
        $user_agent['continent']=$geoip->getContinent();
        $user_agent['location']=$geoip->getLocation();
        return $user_agent;
	}
}