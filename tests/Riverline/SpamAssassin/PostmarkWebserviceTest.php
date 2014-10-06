<?php
/**
 * User: Romain Cambien
 * Date: 27/02/14
 * Time: 12:48
 */

namespace Riverline\SpamAssassin;

class PostmarkWebserviceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PostmarkWebservice
     */
    protected $postmark;

    public function setUp()
    {
        $this->postmark = new PostmarkWebservice(RawEmail::getGTUBE(), true);
    }

    public function testError()
    {
        $this->setExpectedException('RuntimeException', 'SpamAssassin error occured');

        $postmark = new PostmarkWebservice('', true);
        $postmark->getScore();
    }

    public function testSuccess()
    {
        $postmark = new PostmarkWebservice(RawEmail::getGTUBE(), true);
        $score = $postmark->getScore();

        $this->assertEquals(1000, $score);

        $this->assertContains('Generic Test for Unsolicited Bulk Email', $postmark->getReport());
    }
}