<?php

namespace DanHanly\Musician\Commands;

use DanHanly\Musician\Formatters\TopTenFormatter;
use DanHanly\Musician\Matchers\ArtistNameMatcher;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FavouriteArtistSimple extends Command
{
    /**
     * @var static
     */
    protected $csv;

    /**
     * @var int
     */
    protected $resultCount = 10;

    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this->setName('artist:simple')
            ->setDescription('Retrieve Favourite Artist by Rating Count')
            ->addArgument('csv', InputArgument::REQUIRED, 'CSV file path');
    }

    /**
     * Execute Command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = $input->getArgument('csv');
        $this->csv = Reader::createFromPath($filePath);

        $artists = [];

        $this->csv->each(function ($row) use ($output, &$artists) {
            // Get Rows
            $artist = ArtistNameMatcher::key($row[0]);
            $rating = $row[6];
            // Is the artist already in the array?
            if (isset($artists[$artist]) === true) {
                if ($rating === 'thumbs-up') {
                    $artists[$artist] += 1;
                }
            } else {
                if ($rating === 'thumbs-up') {
                    $artists[$artist] = 1;
                }
            }
            return true;
        });

        arsort($artists);

        return (new TopTenFormatter)->output($output, $artists);
    }
}
