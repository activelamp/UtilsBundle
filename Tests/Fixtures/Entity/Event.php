<?php


namespace ActiveLAMP\Bundle\UtilsBundle\Tests\Fixtures\Entity;

use ActiveLAMP\Bundle\UtilsBundle\Validation\Constraints as Assert;

/**
 * Class Event
 *
 * @author Bez Hermoso <bez@activelamp.com>
 *
 *
 * @Assert\DateRange(
 *      start="startDate",
 *      end="endDate"
 * )
 *
 */
class Event
{
    protected $startDate;

    protected $endDate;

    /**
     *
     */
    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
}
