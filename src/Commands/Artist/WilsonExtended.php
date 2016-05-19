<?php

namespace DanHanly\Musician\Commands\Artist;

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
        $this->setName('artist:wilson-extended')
            ->setDescription('Retrieve Favourite Artist by Wilson Score, taking into the thumbs-down tracks')
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
                    $artists[$artist]['positive'] += 1;
                } elseif ($rating === 'thumbs-down') {
                    $artists[$artist]['negative'] += 1;
                }
                $artists[$artist]['total'] += 1;
            } else {
                if ($rating === 'thumbs-up') {
                    $artists[$artist]['positive'] = 1;
                    $artists[$artist]['negative'] = 0;
                } elseif ($rating === 'thumbs-down') {
                    $artists[$artist]['positive'] = 0;
                    $artists[$artist]['negative'] = 1;
                } else {
                    $artists[$artist]['positive'] = 0;
                    $artists[$artist]['negative'] = 0;
                }
                $artists[$artist]['total'] = 1;
            }
            return true;
        });

        foreach ($artists as $artist => $data) {
            $wilsonUpScore = (new WilsonConfidenceIntervalCalculator)->getScore($data['positive'], $data['total']);
            $wilsonDownScore = (new WilsonConfidenceIntervalCalculator)->getScore($data['negative'], $data['total']);
            $summativeScore = $wilsonUpScore - $wilsonDownScore;
            $scores[$artist] = round($summativeScore, 3);
        }

        arsort($scores);

        $output->writeln("Your Favourite Artists are... ");
        return (new TopTenFormatter)->output($output, $scores);
    }
}
