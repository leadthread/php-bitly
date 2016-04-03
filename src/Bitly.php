<?php

namespace Zenapply\Bitly;

use Zenapply\Bitly\Exceptions\BitlyException;
use Zenapply\Request\HttpRequest;
use Zenapply\Request\CurlRequest;

class Bitly
{
    const V3 = 'v3';

    protected $token;
    protected $host;
    protected $version;
    protected $request;

    /**
     * Creates a Calendly instance that can register and unregister webhooks with the API
     * @param string $token   The API token to use
     * @param string $version The API version to use
     * @param string $host    The Host URL
     * @param string $request The HttpRequest instance that will handle the request
     */
    public function __construct($token, $version = self::V3, $host = "api-ssl.bitly.com", HttpRequest $request = null){
        $this->request = $request;
        $this->token = $token;
        $this->version = $version;
        $this->host = $host;
    }

    public function shorten($url,$encode = true)
    {
        if($encode){
            $url = urlencode($url);
        }

        if (empty($url)) {
            throw new BitlyException("The URL is empty!");
        }

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
     * Builds the request URL to the Bitly API for a specified action
     * @param  string $action The long URL
     * @param  string $action The API action
     * @return string         The URL
     */
    protected function buildRequestUrl($url,$action = "shorten"){
        return "https://{$this->host}/{$this->version}/{$action}?access_token={$this->token}&format=json&longUrl={$url}";
    }

    /**
     * Returns the HttpRequest instance
     * @param  string $url The URL to request
     * @return HttpRequest
     */
    protected function getRequest($url){
        $request = $this->request;
        if(!$request instanceof HttpRequest){
            $request = new CurlRequest($url);
        }
        return $request;
    }

    /**
     * Executes a CURL request to the Bitly API
     * @param  string $url    The URL to send to
     * @return mixed          The response data
     */ 
    protected function exec($url)
    {
        $request = $this->getRequest($url);

        $request->setOptionArray([
            CURLOPT_URL => $url,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $result = $request->execute();
        $request->close();

        return $this->handleResponse($result);
    }
}
