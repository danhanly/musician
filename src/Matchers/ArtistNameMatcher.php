<?php

namespace DanHanly\Musician\Matchers;

class ArtistNameMatcher implements Matcher
{
    /**
     * @var array
     */
    public static $replacers = [
        ' and '        => ' & ',
        ' The '        => ' the ',
        'マキシマム ザ ホルモン' => 'Maximum the Hormone',
        'Braves'       => 'BRÅVES',
    ];

    public static $clippers = [
        ' feat ',
        ' feat. ',
        ' Feat ',
        ' Feat. ',
        ' ft ',
        ' ft. ',
        ' Ft ',
        ' Ft. ',
    ];

    /**
     * Returns a keys that has been matched and normalised.
     *
     * @param $current
     *
     * @return string
     */
    public static function key($current)
    {
        // Replace Common Issues
        foreach (self::$replacers as $from => $to) {
            $current = str_replace($from, $to, $current);
        }

        // Clip Artist Name
        foreach (self::$clippers as $delimiter) {
            $match = strstr($current, $delimiter, true);
            if ($match !== false) {
                $current = $match;
            }
        }

        return $current;
    }
}
