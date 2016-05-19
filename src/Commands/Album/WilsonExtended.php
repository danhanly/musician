<?php

namespace DanHanly\Musician\Commands\Album;

use DanHanly\Musician\Formatters\TopTenFormatter;
use DanHanly\Musician\Libraries\WilsonConfidenceIntervalCalculator;
use DanHanly\Musician\Matchers\ArtistNameMatcher;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WilsonExtended extends Command
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
        $this->setName('album:wilson-extended')
            ->setDescription('Retrieve Favourite Album by Wilson Score, taking into the thumbs-down tracks')
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
            // Is the album already in the array?
            if (isset($albums[$key]) === true) {
                if ($rating === 'thumbs-up') {
                    $albums[$key]['positive'] += 1;
                } elseif ($rating === 'thumbs-down') {
                    $albums[$key]['negative'] += 1;
                }
                $albums[$key]['total'] += 1;
            } else {
                if ($rating === 'thumbs-up') {
                    $albums[$key]['positive'] = 1;
                    $albums[$key]['negative'] = 0;
                } elseif ($rating === 'thumbs-down') {
                    $albums[$key]['positive'] = 0;
                    $albums[$key]['negative'] = 1;
                } else {
                    $albums[$key]['positive'] = 0;
                    $albums[$key]['negative'] = 0;
                }
                $albums[$key]['total'] = 1;
            }
            return true;
        });

        foreach ($albums as $key => $data) {
            $wilsonUpScore = (new WilsonConfidenceIntervalCalculator)->getScore($data['positive'], $data['total']);
            $wilsonDownScore = (new WilsonConfidenceIntervalCalculator)->getScore($data['negative'], $data['total']);
            $summativeScore = $wilsonUpScore - $wilsonDownScore;
            $scores[$key] = round($summativeScore, 3);
        }

        arsort($scores);

        $output->writeln("Your Favourite Albums are... ");
        return (new TopTenFormatter)->output($output, $scores);
    }
}
