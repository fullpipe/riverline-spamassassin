<?php
/**
 * User: Romain Cambien
 * Date: 27/02/14
 * Time: 12:29
 */

namespace Riverline\SpamAssassin;

interface SpamAssassinInterface
{
    /**
     * Get the SpamAssassin score
     * @return float
     */
    public function getScore();

    /**
     * Get the SpamAssassin full report
     * @return mixed
     */
    public function getReport();

    /**
     * Get report as associated array
     * @param boolean Skip zero scored messages
     * @return array
     */
    public function getReportAsArray($skipZeros = true);
}