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
        $fixture = $this->getBitlyWithMockedHttpRequest('{"status_code":200,"data":{"url":"short.com"}}');
        $result = $this->invokeMethod($fixture,'buildRequestUrl',['https://google.com','testAction']);
        $this->assertEquals("https://foo.com/v1/testAction?access_token=1234jkljqwe12s5tadf&format=json&longUrl=https://google.com",$result);
    }

    public function testItCorrectsAUrlByAddingAProtocolToIt(){
        $r = new Bitly("token");
        $result = $this->invokeMethod($r,'fixUrl',['google.com',false]);
        $this->assertEquals("http://google.com",$result);
    }

    public function testItDoesntAddAProtocolOnToAUrlWithAProtocol(){
        $r = new Bitly("token");
        $result = $this->invokeMethod($r,'fixUrl',['https://google.com',false]);
        $this->assertEquals("https://google.com",$result);
    }

    public function testItEncodesAUrl(){
        $r = new Bitly("token");
        $result = $this->invokeMethod($r,'fixUrl',['https://google.com',true]);
        $this->assertEquals("https%3A%2F%2Fgoogle.com",$result);
    }

    public function testMethodShorten(){
        $fixture = $this->getBitlyWithMockedHttpRequest('{"status_code":200,"data":{"url":"short.com"}}');
        $result = $fixture->shorten("long.com");
        $this->assertEquals("short.com",$result);
    }

    public function testMethodShortenAddsOnProtocol(){
        $fixture = $this->getBitlyWithMockedHttpRequest('{"status_code":200,"data":{"url":"short.com"}}');
        $result = $fixture->shorten("long.com");
        $this->assertEquals("short.com",$result);
    }

    public function testMethodShortenThrowsExceptionWhenUrlIsEmpty(){
        $this->setExpectedException(BitlyException::class);
        $fixture = $this->getBitlyWithMockedHttpRequest('{"status_code":200,"data":{"url":"short.com"}}');
        $result = $fixture->shorten("");
    }

    public function testMethodShortenThrowsExceptionWhenStatusCodeIsNot200(){
        $this->setExpectedException(BitlyException::class);
        $fixture = $this->getBitlyWithMockedHttpRequest('{"status_code":500,"status_txt":"An Error occurred!"}');
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

        $mock = $this->getMock(Bitly::class,[],["Token","v1","foo.com",$http]);

        // create class under test using $http instead of a real CurlRequest
        return $mock;
    }

}
