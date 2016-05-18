<?php

namespace DanHanly\Musician\Matchers;

/**
 * Interface Matcher
 *
 * @package DanHanly\Musician\Matchers
 */
interface Matcher
{
    /**
     * Returns a keys that has been matched and normalised.
     *
     * @param $current
     *
     * @return string
     */
    public static function key($current);
}
