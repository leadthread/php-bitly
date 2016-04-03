<?php

namespace Zenapply\Bitly\Tests;

use Zenapply\Bitly\Bitly;
use Zenapply\Bitly\Exceptions\BitlyException;
use Zenapply\Request\HttpRequest;

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

    public function testMethodShortenThrowsExceptionWhenStatusCodeIsNot200(){
        $this->setExpectedException(BitlyException::class);
        $fixture = $this->getBitlyWithMockedHttpRequest('{"status_code":500,"status_txt":"An Error occurred!"');
        $result = $fixture->shorten("long.com");
    }

    protected function getBitlyWithMockedHttpRequest($data){
        $http = $this->getMock(HttpRequest::class);

        $http->expects($this->any())
             ->method('execute')
             ->will($this->returnValue($data));

        // create class under test using $http instead of a real CurlRequest
        return new Bitly("Token","v1","foo.com",$http);
    }

}
