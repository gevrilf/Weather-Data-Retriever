<?php

//IP Address
$userIp = $_SERVER['REMOTE_ADDR'];

//API URL
$apiUrl = 'https://api.ipbase.com/v1/json/'.$userIp;

//create a new cURL resourse with URL
$ch = curl_init($apiUrl);

//return response instead of outputting
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//Execute API request
$apiResponse = curl_exec($ch);

//close cURL resource
curl_close($ch);

//Retrieve IP data
$ipData = json_decode($apiResponse, true);

//If ip data is not empty run program to get user data
if(!empty($ipData)){
    $countryCode=$ipData['country_code'];
    $countryName=$ipData['country_name'];
    $regionCode=$ipData['region_code'];
    $regionName=$ipData['region_name'];
    $city=$ipData['city'];
    $zipcode=$ipData['zip_code'];
    $latitude=$ipData['latitude'];
    $longitude=$ipData['longitude'];
    $timezone=$ipData['time_zone'];
    date_default_timezone_set ($timezone); 

    //api key from weather api
    $apikey = 'enter api here';
    //access ip api
    $latlong = explode(",", file_get_contents('https://ipapi.co/'.$userIp.'/latlong/'));
    // get weather api info
    $weather = file_get_contents('http://api.openweathermap.org/data/2.5/weather?lat='.$latlong[0].'&lon='.$latlong[1].'&appid='.$apikey);

    //json decode weather data so we can parse the data
    $data=json_decode($weather, true);

    //print data array
    print_r ($weather);

    //weather data
    $tempC = round($data['main']['temp'] - 273.15, 1);
    $desc = $data['weather'][0]['description'];
    $visib = $data['visibility'];
    $press = $data['main']['pressure'];
    $windspd = $data['wind']['speed'];
    $windbear = $data['wind']['deg'];
    $winddir = degToCompass($windbear);
    $sunrise = date("Y-m-d H:i:s", $data['sys']['sunrise']);
    $sunset = date("Y-m-d H:i:s", $data['sys']['sunset']);
    $humidity = $data['main']['humidity'];

    echo"<br><br>";

    //Output user information/
    echo"<br>IP: $userIp";
    echo"<br>city: $city";
    echo"<br>State: $regionName";
    echo"<br>Country: $countryName";
    echo"<br>Description: $desc";
    echo"<br>Temp: $tempC C " .round(($tempC*(9/5))+ 32, 1)." F";
    echo"<br>Humidity: $humidity %";
    echo"<br>Visibility: $visib m = ".round($visib/1609, 1)." miles";
    echo"<br>Pressure: $press hpa";
    echo"<br>Wind Speed: $windspd m/s ".round($windspd*2.237, 1)." mph";
    echo"<br>Wind Direction: $winddir($windbear)";
    echo"<br>Time Zone: $timezone";
    echo"<br>Sunrise: $sunrise";
    echo"<br>Sunset: $sunset";


}else{
    //when Brower cant get IP
    echo "You are a ghost I can't find you!";
}

//degree to direction calculator
function degToCompass($windbear)
{
 $cardinalDirections = array("N","NNE","NE","ENE","E","ESE", "SE", "SSE","S","SSW","SW","WSW","W","WNW","NW","NNW"); 
 $i = ($windbear/22.5)+.5;
 $direction = $cardinalDirections[($i%16)];
 return $direction;
}


 ?>
