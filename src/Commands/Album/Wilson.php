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

class Wilson extends Command
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
        $this->setName('album:wilson')
            ->setDescription('Retrieve Favourite Album by Lower Bound Wilson Confidence Interval')
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
                }
                $albums[$key]['total'] += 1;
            } else {
                if ($rating === 'thumbs-up') {
                    $albums[$key]['positive'] = 1;
                } else {
                    $albums[$key]['positive'] = 0;
                }
                $albums[$key]['total'] = 1;
            }
            return true;
        });

        $scores = [];

        foreach ($albums as $key => $data) {
            $wilsonScore = (new WilsonConfidenceIntervalCalculator)->getScore($data['positive'], $data['total']);
            $scores[$key] = round($wilsonScore, 3);
        }

        arsort($scores);

        $output->writeln("Your Favourite Albums are... ");
        return (new TopTenFormatter)->output($output, $scores);
    }
}
