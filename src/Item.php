<?php

namespace duncan3dc\Cache;

use Psr\Cache\CacheItemInterface;

class Item implements CacheItemInterface
{
    /**
     * @var string $key The unique key of this item.
     */
    private $key;

    /**
     * @var mixed $value The current value of this item.
     */
    private $value;

    /**
     * @var int $expiration The unix timestamp the value expires (zero for never expires).
     */
    private $expiration = 0;


    /**
     * Create a new instance.
     *
     * @param string $key The unique key of this item
     * @param mixed $value The current value of this item
     */
    public function __construct($key, $value = null)
    {
        $this->key = $key;
        if ($value !== null) {
            $this->set($value);
        }
    }


    /**
     * Returns the key for the current cache item.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }


    /**
     * Retrieves the value of the item from the cache associated with this object's key.
     *
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }


    /**
     * Confirms if the cache item lookup resulted in a cache hit.
     *
     * @return bool
     */
    public function isHit()
    {
        if ($this->value === null) {
            return false;
        }

        if (!$this->expiration) {
            return true;
        }

        if ($this->expiration < time()) {
            return false;
        }

        return true;
    }


    /**
     * Sets the value represented by this cache item.
     *
     * @param mixed $value The serializable value to be stored
     *
     * @return $this
     */
    public function set($value)
    {
        $this->value = $value;

        return $this;
    }


    /**
     * Sets the expiration time for this cache item.
     *
     * @param \DateTimeInterface $expiration The point in time after which the item MUST be considered expired
     *
     * @return $this
     */
    public function expiresAt($expiration)
    {
        $this->expiration = $expiration->getTimestamp();

        return $this;
    }


    /**
     * Sets the expiration time for this cache item.
     *
     * @param int|\DateInterval $time The period of time from the present after which the item MUST be considered expired
     *
     * @return $this
     */
    public function expiresAfter($time)
    {
        if ($time instanceof \DateInterval) {
            $seconds = $time->format("%s");
            if ($time->invert) {
                $seconds *= -1;
            }
            $time = $seconds;
        }

        $this->expiration = time() + $time;

        return $this;
    }
}
