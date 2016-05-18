<?php

namespace DanHanly\Musician;

use DanHanly\Musician\Commands\FavouriteArtistDifferential;
use DanHanly\Musician\Commands\FavouriteArtistSimple;
use DanHanly\Musician\Commands\FavouriteArtistWilson;
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
        
        $application->add(new FavouriteArtistSimple());
        $application->add(new FavouriteArtistDifferential());
        $application->add(new FavouriteArtistWilson());

        return $application;
    }
}
