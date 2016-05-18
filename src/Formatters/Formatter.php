<?php

namespace DanHanly\Musician\Formatters;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface Formatter
 *
 * @package DanHanly\Musician\Formatters
 */
interface Formatter
{
    /**
     * Output the
     *
     * @param OutputInterface $output
     * @param array $data
     *
     * @return mixed
     */
    public function output(OutputInterface $output, array $data);
}
