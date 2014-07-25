<?php


namespace ActiveLAMP\Bundle\UtilsBundle\Validation\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class DateRange
 *
 * @Annotation
 * @Target({"CLASS"})
 *
 * @author Bez Hermoso <bez@activelamp.com>
 */
class DateRange extends Constraint
{
    /**
     * @var string The property which contains the start date.
     */
    public $start;

    /**
     * @var string The property which contains the end date.
     */
    public $end;

    public $startMessage = 'Start date should be less than or equal to {{ limit }}';

    public $endMessage = 'End date should be greater than or equal to {{ limit }}';

    public $emptyStartMessage = 'Start date cannot be empty';

    public $emptyEndMessage = 'End date cannot be empty';

    public $invalidMessage = 'Invalid date range';

    /**
     * @var string The property to attach the error message on.
     */
    public $errorOnProperty = false;

    public function getRequiredOptions()
    {
        return array(
            'start',
            'end',
        );
    }

    public function getTargets()
    {
        return static::CLASS_CONSTRAINT;
    }
}
