<?php

namespace Zenapply\Bitly;

use Zenapply\Bitly\Exceptions\BitlyException;
use Zenapply\Request\HttpRequest;
use Zenapply\Request\CurlRequest;

class Bitly
{
    const V3 = 'v3';

    /**
     * Creates a Calendly instance that can register and unregister webhooks with the API
     * @param string $token   The API token to use
     * @param string $format  The data format that will be returned; txt, json, or xml.
     * @param string $version The API version to use
     * @param string $host    The Host URL
     * @param string $request The HttpRequest instance that will handle the request
     */
    public function __construct($token,$format = 'txt',$version = self::V3,$host = "api-ssl.bitly.com", HttpRequest $request = null){
        $this->request = $request;
        $this->token = $token;
        $this->version = $version;
        $this->format = $format;
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

        $data = $this->exec($this->buildRequestUrl());
            
        return $data;
    }

    /**
     * Returns the response data or throws an Exception if it was unsuccessful
     * @param  string  $data   The data from the response
     * @param  integer $code   The HTTP response code
     * @return array
     */
    protected function handleResponse($data,$code){
        $match_arr = [];
        preg_match("/bit.ly/", $data, $match_arr);
        if(count($match_arr) === 0){
            throw new BitlyException("Bitly ".$data);
        }
        return $data;
    }

    /**
     * Builds the request URL to the Bitly API for a specified action
     * @param  string $action The API action
     * @return string         The URL
     */
    protected function buildRequestUrl($action = "shorten"){
        return "https://{$this->host}/{$this->version}/{$action}?access_token={$this->token}&format={$this->format}&longUrl={$url}";
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
        $code   = $request->getInfo(CURLINFO_HTTP_CODE);
        
        $request->close();

        return $this->handleResponse($result,$code);
    }
}
