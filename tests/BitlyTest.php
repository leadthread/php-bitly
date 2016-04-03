<?php

namespace Zenapply\Bitly\Tests;

use Zenapply\Bitly\Bitly;

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

}
