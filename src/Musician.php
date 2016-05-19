<?php

namespace DanHanly\Musician;

use DanHanly\Musician\Commands\FavouriteArtistDifferential;
use DanHanly\Musician\Commands\FavouriteArtistCount;
use DanHanly\Musician\Commands\FavouriteArtistWilson;
use DanHanly\Musician\Commands\FavouriteArtistWilsonExtended;
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
        
        $application->add(new FavouriteArtistCount());
        $application->add(new FavouriteArtistDifferential());
        $application->add(new FavouriteArtistWilson());
        $application->add(new FavouriteArtistWilsonExtended());

        return $application;
    }
}
