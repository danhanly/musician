<?php

namespace DanHanly\Musician;

use DanHanly\Musician\Commands\Artist;
use DanHanly\Musician\Commands\Album;
use Symfony\Component\Console\Application;

class Musician
{
    /**
     * Registers the application commands
     *
     * @return \Symfony\Component\Console\Application
     */
    public static function init()
    {
        $application = new Application();

        $application->add(new Artist\Count());
        $application->add(new Artist\Differential());
        $application->add(new Artist\Wilson());
        $application->add(new Artist\WilsonExtended());

        $application->add(new Album\Count());
        $application->add(new Album\Differential());
        $application->add(new Album\Wilson());
        $application->add(new Album\WilsonExtended());

        return $application;
    }
}
