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

}
