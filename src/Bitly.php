<?php

namespace Zenapply\Bitly;

use Zenapply\Bitly\Exceptions\BitlyException;
use GuzzleHttp\Client;

class Bitly
{
    const V3 = 'v3';

    protected $token;
    protected $host;
    protected $version;
    protected $client;

    /**
     * Creates a Calendly instance that can register and unregister webhooks with the API
     * @param string $token   The API token to use
     * @param string $version The API version to use
     * @param string $host    The Host URL
     * @param string $client  The Client instance that will handle the http request
     */
    public function __construct($token, $version = self::V3, $host = "api-ssl.bitly.com", Client $client = null){
        $this->client = $client;
        $this->token = $token;
        $this->version = $version;
        $this->host = $host;
    }

    public function shorten($url, $encode = true)
    {
        if (empty($url)) {
            throw new BitlyException("The URL is empty!");
        }

        $url = $this->fixUrl($url, $encode);

        $data = $this->exec($this->buildRequestUrl($url));
            
        return $data['data']['url'];
    }

    /**
     * Returns the response data or throws an Exception if it was unsuccessful
     * @param  string  $data   The data from the response
     * @return array
     */
    protected function handleResponse($data){
        $data = json_decode($data,true);
        if($data['status_code']>=300 || $data['status_code']<200){
            throw new BitlyException($data['status_txt']);
        }
        return $data;
    }

    /**
     * Returns a corrected URL
     * @param  string  $url    The URL to modify
     * @param  boolean $encode Whether or not to encode the URL
     * @return string          The corrected URL
     */
    protected function fixUrl($url, $encode){
        if(strpos($url, "http") !== 0){
            $url = "http://".$url;
        }

        if($encode){
            $url = urlencode($url);
        }

        return $url;
    }

    /**
     * Builds the request URL to the Bitly API for a specified action
     * @param  string $action The long URL
     * @param  string $action The API action
     * @return string         The URL
     */
    protected function buildRequestUrl($url,$action = "shorten"){
        return "https://{$this->host}/{$this->version}/{$action}?access_token={$this->token}&format=json&longUrl={$url}";
    }

    /**
     * Returns the Client instance
     * @return Client
     */
    protected function getRequest(){
        $client = $this->client;
        if(!$client instanceof Client){
            $client = new Client();
        }
        return $client;
    }

    /**
     * Executes a CURL request to the Bitly API
     * @param  string $url    The URL to send to
     * @return mixed          The response data
     */ 
    protected function exec($url)
    {
        $client = $this->getRequest();
        $response = $client->request('GET',$url);
        return $this->handleResponse($response->getBody());
    }
}
