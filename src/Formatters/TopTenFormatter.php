<?php

namespace DanHanly\Musician\Formatters;

use Symfony\Component\Console\Output\OutputInterface;

class TopTenFormatter implements Formatter
{

    /**
     * Output the
     *
     * @param OutputInterface $output
     * @param array $data
     *
     * @return mixed
     */
    public function output(OutputInterface $output, array $data)
    {
        $output->writeln("Your Favourite Artists are... ");

        $keys = array_keys($data);
        $values = array_values($data);

        for ($i = 0; $i < 10; $i++) {
            $output->writeln($i + 1 . '. ' . $keys[$i] . ' - ' . $values[$i]);
        }
    }
}
