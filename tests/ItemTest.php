<?php

namespace duncan3dc\CacheTests;

use duncan3dc\Cache\Item;

class ItemTest extends \PHPUnit_Framework_TestCase
{

    public function testGetKey()
    {
        $item = new Item("test");

        $this->assertSame("test", $item->getKey());
    }


    public function testGetNoValue()
    {
        $item = new Item("test");

        $this->assertNull($item->get());
    }


    public function testGetBool()
    {
        $item = new Item("test", false);

        $this->assertFalse($item->get());
    }


    public function testGetString()
    {
        $item = new Item("test", "true");

        $this->assertSame("true", $item->get());
    }


    public function testSet()
    {
        $item = new Item("test");

        $value = (object) [
            "field" =>  "value",
        ];

        $result = $item->set($value);

        $this->assertSame($item, $result);
        $this->assertSame($value, $item->get());
    }


    public function testIsHit1()
    {
        $item = new Item("test");
        $item->set("ok");

        $this->assertTrue($item->isHit());
    }
    public function testIsHit2()
    {
        $item = new Item("test");

        $this->assertFalse($item->isHit());
    }


    public function testExpiresAt1()
    {
        $item = new Item("test");
        $item->set("ok");
        $this->assertTrue($item->isHit());

        $item->expiresAt(new \DateTime("1 second ago"));
        $this->assertFalse($item->isHit());
    }
    public function testExpiresAt2()
    {
        $item = new Item("test");
        $item->set("ok");
        $this->assertTrue($item->isHit());

        $item->expiresAt(new \DateTime("+1 second"));
        $this->assertTrue($item->isHit());
    }


    public function testExpiresAfter1()
    {
        $item = new Item("test");
        $item->set("ok");
        $this->assertTrue($item->isHit());

        $item->expiresAfter(-1);
        $this->assertFalse($item->isHit());
    }
    public function testExpiresAfter2()
    {
        $item = new Item("test");
        $item->set("ok");
        $this->assertTrue($item->isHit());

        $item->expiresAfter(1);
        $this->assertTrue($item->isHit());
    }
    public function testExpiresAfter3()
    {
        $item = new Item("test");
        $item->set("ok");
        $this->assertTrue($item->isHit());

        $interval = new \DateInterval("PT1S");
        $interval->invert = 1;
        $item->expiresAfter($interval);
        $this->assertFalse($item->isHit());
    }
    public function testExpiresAfter4()
    {
        $item = new Item("test");
        $item->set("ok");
        $this->assertTrue($item->isHit());

        $item->expiresAfter(new \DateInterval("PT1S"));
        $this->assertTrue($item->isHit());
    }


}
