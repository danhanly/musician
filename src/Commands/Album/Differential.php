<?php

namespace DanHanly\Musician\Commands\Album;

use DanHanly\Musician\Formatters\TopTenFormatter;
use DanHanly\Musician\Matchers\ArtistNameMatcher;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Differential extends Command
{
    /**
     * @var static
     */
    protected $csv;

    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this->setName('album:differential')
            ->setDescription('Retrieve Favourite Album by Rating Count Differential')
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

        $albums = [];

        $this->csv->each(function ($row) use ($output, &$albums) {
            // Get Rows
            $artist = ArtistNameMatcher::key($row[0]);
            $album = $row[1];
            $rating = $row[6];

            $key = $artist . ' - ' . $album;
            // Is the artist already in the array
            if (isset($albums[$key]) === true) {
                if ($rating === 'thumbs-up') {
                    $albums[$key] += 1;
                } elseif ($rating === 'thumbs-down') {
                    $albums[$key] -= 1;
                }
            } else {
                if ($rating === 'thumbs-up') {
                    $albums[$key] = 1;
                } elseif ($rating === 'thumbs-down') {
                    $albums[$key] = -1;
                }
            }
            return true;
        });

        arsort($albums);

        $output->writeln("Your Favourite Albums are... ");
        return (new TopTenFormatter)->output($output, $albums);
    }
}
