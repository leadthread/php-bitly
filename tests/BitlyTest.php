<?php

namespace Zenapply\Bitly\Tests;

use Zenapply\Bitly\Bitly;
use Zenapply\Bitly\Exceptions\BitlyException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class BitlyTest extends TestCase
{
    protected $request;

    public function testItCreatesAnInstanceOfHttpRequest(){
        $r = new Bitly("token");
        $this->assertInstanceOf(Bitly::class,$r);
    }

    public function testItBuildsCorrectRequestUrl(){
        $r = new Bitly("token");
        $result = $this->invokeMethod($r,'buildRequestUrl',['http://google.com','testAction']);
        $this->assertEquals($result,"https://api-ssl.bitly.com/v3/testAction?access_token=token&format=json&longUrl=http://google.com");
    }

    public function testMethodShorten(){
        $fixture = $this->getBitlyWithMockedHttpRequest('{"status_code":200,"data":{"url":"short.com"}}');
        $result = $fixture->shorten("long.com");
        $this->assertEquals($result,"short.com");
    }

    public function testMethodShortenThrowsExceptionWhenUrlIsEmpty(){
        $this->setExpectedException(BitlyException::class);
        $fixture = $this->getBitlyWithMockedHttpRequest('{"status_code":200,"data":{"url":"short.com"}}');
        $result = $fixture->shorten("");
    }

    public function testMethodShortenThrowsExceptionWhenStatusCodeIsNot200(){
        $this->setExpectedException(BitlyException::class);
        $fixture = $this->getBitlyWithMockedHttpRequest('{"status_code":500,"status_txt":"An Error occurred!"');
        $result = $fixture->shorten("long.com");
    }

    protected function getBitlyWithMockedHttpRequest($data){
        $http = $this->getMock(Client::class);

        $resp = $this->getMock(Response::class);

        $resp->expects($this->any())
             ->method('getBody')
             ->will($this->returnValue($data));

        $http->expects($this->any())
             ->method('request')
             ->will($this->returnValue($resp));

        // create class under test using $http instead of a real CurlRequest
        return new Bitly("Token","v1","foo.com",$http);
    }

}
