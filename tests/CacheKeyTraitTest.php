<?php

namespace duncan3dc\CacheTests;

use duncan3dc\Cache\Exceptions\CacheKeyException;
use PHPUnit\Framework\TestCase;

class CacheKeysTraitTest extends TestCase
{
    private $cache;

    public function setUp()
    {
        $this->cache = new CacheKeys;
    }


    public function validateKeyProvider()
    {
        $keys = [
            "ok"        =>  null,
            "ok_1_2"    =>  null,
            "ABC-abc"   =>  null,
            "NOT_OK_@"  =>  "Cache key contains invalid characters",
            "No Spaces" =>  "Cache key contains invalid characters",
            "!NOPE"     =>  "Cache key contains invalid characters",
            "/root"     =>  "Cache key contains invalid characters",
            "\\escape"  =>  "Cache key contains invalid characters",
        ];
        foreach ($keys as $key => $expected) {
            yield [$key, $expected];
        }
        yield [false, "Cache key must be a string"];
        yield [null, "Cache key must be a string"];
        yield [[], "Cache key must be a string"];
        yield [new \DateTime, "Cache key must be a string"];
    }
    /**
     * @dataProvider validateKeyProvider
     */
    public function testValidateKey($key, $expected)
    {
        if ($expected !== null) {
            $this->expectException(CacheKeyException::class);
            $this->expectExceptionMessage($expected);
        }

        $this->cache->validateKey($key);
        $this->assertTrue(true);
    }
    public function testValidateKeyOnLongKey()
    {
        $this->expectException(CacheKeyException::class);
        $this->cache->validateKey(str_repeat('key_length_is_greater_than_64', 3));
    }
}
