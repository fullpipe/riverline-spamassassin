<?php
/**
 * User: Romain Cambien
 * Date: 27/02/14
 * Time: 12:31
 */

namespace Riverline\SpamAssassin;

use Guzzle\Http\Client;

class PostmarkWebservice implements SpamAssassinInterface
{
    const WEBSERVICE_URL = 'http://spamcheck.postmarkapp.com/filter';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var bool
     */
    protected $longReport;

    /**
     * @var  string
     */
    protected $report;

    /**
     * @var string
     */
    protected $score;

    /**
     * @var string
     */
    protected $email;

    /**
     * @param string $rawEmail Email as string with headers
     * @param bool $longReport Request a long report
     */
    public function __construct($rawEmail, $longReport = false)
    {
        $this->client     = new Client();
        $this->longReport = $longReport;
        $this->email      = $rawEmail;
    }

    /**
     * {@inheritdoc}
     */
    public function getScore()
    {
        $this->requestReport();

        return $this->score;
    }

    /**
     * {@inheritdoc}
     */
    public function getReport()
    {
        $this->requestReport();

        return $this->report;
    }

    /**
     * {@inheritdoc}
     */
    public function getReportAsArray($skipZeros = true)
    {
        $this->requestReport();
        $result = array();

        if (null !== $this->report) {
            preg_match_all("/(\-?\d\.\d)\s(\w*)\s*(.*)/", $this->report, $matches);
            if (!empty($matches)) {
                foreach ($matches[0] as $key => $value) {
                    $row = array(
                        'score' => floatval($matches[1][$key]),
                        'name' => $matches[2][$key],
                        'info' => $matches[3][$key]
                    );

                    if (!$skipZeros || $row['score'] != 0) {
                        $result[] = $row;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Make request to webservice
     */
    public function requestReport()
    {
        if (null !== $this->score) {
            return;
        }

        $request = $this->client->post(
            self::WEBSERVICE_URL,
            array('Content-Type' => 'application/json'),
            json_encode(array(
                'email'   => $this->email,
                'options' => ($this->longReport?'long':'short')
            ))
        );

        $response = $request->send()->json();

        if (!$response['success']) {
            throw new \RuntimeException($response['message']);
        } elseif (!isset($response['score'])) {
            throw new \RuntimeException('Missing score');
        } else {
            $this->report = isset($response['report']) ? $response['report'] : null ;
            $this->score = $response['score'];
        }
    }
}
