<?php

/**
 * Yelp API v2.0 code sample.
 *
 * This program demonstrates the capability of the Yelp API version 2.0
 * by using the Search API to query for businesses by a search term and location,
 * and the Business API to query additional information about the top result
 * from the search query.
 * 
 * Please refer to http://www.yelp.com/developers/documentation for the API documentation.
 * 
 * This program requires a PHP OAuth2 library, which is included in this branch and can be
 * found here:
 *      http://oauth.googlecode.com/svn/code/php/
 * 
 * Sample usage of the program:
 * `php sample.php --term="bars" --location="San Francisco, CA"`
 */

// Enter the path that the oauth library is in relation to the php file
require_once('lib/OAuth.php');
require_once("php/curl.php");

// Set your OAuth credentials here  
// These credentials can be obtained from the 'Manage API Access' page in the
// developers documentation (http://www.yelp.com/developers)
$CONSUMER_KEY = "gOTg5RXESDf2Uht_6Ev1Dg";
$CONSUMER_SECRET = "OJevGnYonXzuKi5OKzo0SiiB1-o";
$TOKEN = "U8zcUR9xXn3UW5uc6eNG8ekLyz5maTku";
$TOKEN_SECRET = "YnT2-loxw1fM2VUIUX9YbgwdDiM";


$API_HOST = 'api.yelp.com';
$DEFAULT_TERM = null;
$DEFAULT_LOCATION = 'Charleston, SC';
$SEARCH_LIMIT = 20;
$SEARCH_PATH = '/v2/search/';
$BUSINESS_PATH = '/v2/business/';

$yelpResults = "";

/** 
 * Makes a request to the Yelp API and returns the response
 * 
 * @param    $host    The domain host of the API 
 * @param    $path    The path of the APi after the domain
 * @return   The JSON response from the request      
 */
function request($host, $path) {
    $unsigned_url = "https://" . $host . $path;

    // Token object built using the OAuth library
    $token = new OAuthToken($GLOBALS['TOKEN'], $GLOBALS['TOKEN_SECRET']);

    // Consumer object built using the OAuth library
    $consumer = new OAuthConsumer($GLOBALS['CONSUMER_KEY'], $GLOBALS['CONSUMER_SECRET']);

    // Yelp uses HMAC SHA1 encoding
    $signature_method = new OAuthSignatureMethod_HMAC_SHA1();

    $oauthrequest = OAuthRequest::from_consumer_and_token(
        $consumer, 
        $token, 
        'GET', 
        $unsigned_url
    );
    
    // Sign the request
    $oauthrequest->sign_request($signature_method, $consumer, $token);
    
    // Get the signed URL
    $signed_url = $oauthrequest->to_url();
    
    // Send Yelp API Call
    try {
        $ch = curl_init($signed_url);
        if (FALSE === $ch)
            throw new Exception('Failed to initialize');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $data = curl_exec($ch);

        if (FALSE === $data)
            throw new Exception(curl_error($ch), curl_errno($ch));
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (200 != $http_status)
            throw new Exception($data, $http_status);

        curl_close($ch);
    } catch(Exception $e) {
        trigger_error(sprintf(
            'Curl failed with error #%d: %s',
            $e->getCode(), $e->getMessage()),
            E_USER_ERROR);
    }
    
    return $data;
}

/**
 * Query the Search API by a search term and location 
 * 
 * @param    $term        The search term passed to the API 
 * @param    $location    The search location passed to the API 
 * @return   The JSON response from the request 
 */
function search($term, $location, $isDeal) {
    $url_params = array();
    
    $url_params['term'] = $term ?: $GLOBALS['DEFAULT_TERM'];
    $url_params['location'] = $location?: $GLOBALS['DEFAULT_LOCATION'];
    $url_params['limit'] = $GLOBALS['SEARCH_LIMIT'];
    if ($isDeal)
        $url_params['deals_filter'] = true;
    $search_path = $GLOBALS['SEARCH_PATH'] . "?" . http_build_query($url_params);
    
    return request($GLOBALS['API_HOST'], $search_path);
}

/**
 * Query the Business API by business_id
 * 
 * @param    $business_id    The ID of the business to query
 * @return   The JSON response from the request 
 */
function get_business($business_id) {
    $business_path = $GLOBALS['BUSINESS_PATH'] . urlencode($business_id);
    
    return request($GLOBALS['API_HOST'], $business_path);
}

/**
 * Queries the API by the input values from the user 
 * 
 * @param    $term        The search term to query
 * @param    $location    The location of the business to query
 */
function query_api($term, $location, $isDeal) {     
    $response = json_decode(search($term, $location, $isDeal));
    
    $total = count($response->businesses);
    $randomNr = rand(0, $total-1);

    $business_id = $response->businesses[$randomNr]->id;
    
    //$raw = $response->businesses[$randomNr];

    $name = $response->businesses[$randomNr]->name;
    $address = $response->businesses[$randomNr]->location->address[0];
    $rating = $response->businesses[$randomNr]->rating;
    $url = $response->businesses[$randomNr]->mobile_url;
    //$isClosed = $response->businesses[$randomNr]->is_closed;
    $phone = $response->businesses[$randomNr]->phone;
    $categories = $response->businesses[$randomNr]->categories;
    $website = $response->businesses[$randomNr]->mobile_url;
    $category = '';
    $websiteText = '';

    foreach ($categories as $cat) {
        if ($cat[1] === "mexican")
            $category = "mexican food";
        if ($cat[1] === "african")
            $category = "african food";
        if ($cat[1] === "newamerican" || $cat[1] === "tradamerican" || $cat[1] === "chicken_wings" || $cat[1] === "hotdog" || $cat[1] === "steak" || $cat[1] === "burgers")
            $category = "american food";
        if ($cat[1] === "asianfusion" || $cat[1] === "sushi" || $cat[1] === "thai")
            $category = "asian food";
        if ($cat[1] === "bbq")
            $category = "bbq";
        if ($cat[1] === "breakfast_brunch")
            $category = "brunch";
        if ($cat[1] === "cafes" || $cat[1] === "cafeteria")
            $category = "cafe";
        if ($cat[1] === "chinese")
            $category = "chinese food";
        if ($cat[1] === "cuban")
            $category = "cuban food";
        if ($cat[1] === "delis")
            $category = "delis";
        if ($cat[1] === "food_court")
            $category = "food court";
        if ($cat[1] === "gluten_free")
            $category = "gluten free";
        if ($cat[1] === "indonesian")
            $category = "indonesian food";
        if ($cat[1] === "korean")
            $category = "korean food";
        if ($cat[1] === "japanese")
            $category = "japanese food";
        if ($cat[1] === "italian" || $cat[1] === "pizza")
            $category = "italian food";
        if ($cat[1] === "japanese")
            $category = "japanese food";
        if ($cat[1] === "vegan")
            $category = "vegan";            
    }

    if (!empty($category)){
        $category = "(".$category.")";
    }

    if (!empty($category)){
        $websiteText = ' or visit website: '. shorten($website);
    }

    if ($isDeal){
        $deal_title = $response->businesses[$randomNr]->deals[0]->title;
        $what_you_get = $response->businesses[$randomNr]->deals[0]->what_you_get;

        //cleaning default Yelp response
        $what_you_get = str_replace('Print out your voucher, or redeem on your phone with the <a href="',"Find your voucher on Yelp: ",$what_you_get);
        $what_you_get = str_replace('">Yelp app</a>.',"",$what_you_get);

        $yelpResults = $name.$category.' on '.$address.' has '.$deal_title.' deal! '.htmlentities($what_you_get);
    }
    else
        $yelpResults = $name.$category.' on '.$address.'. It has '.$rating.' star rating! Call them '.$phone.$websiteText;

    return $yelpResults;
    
}

    $longopts  = array(
        "term::",
        "location::",
    );
        
    $options = getopt("", $longopts);

?>