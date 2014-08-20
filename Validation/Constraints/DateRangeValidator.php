<?php


namespace ActiveLAMP\Bundle\UtilsBundle\Validation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

/**
 * Class DateRangeValidator
 *
 * @author Bez Hermoso <bez@activelamp.com>
 */
class DateRangeValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed                $value      The value that should be validated
     * @param DateRange|Constraint $constraint The constraint for the validation
     *
     * @throws \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     * @api
     */
    public function validate($value, Constraint $constraint)
    {
        $reflection = new \ReflectionObject($value);

        if ($reflection->hasProperty($constraint->start) === false) {
            throw new ConstraintDefinitionException(
                sprintf(
                    'The object "%s" does not have a "%s" property.',
                    $reflection->getName(),
                    $constraint->start
                )
            );
        }

        if ($reflection->hasProperty($constraint->end) === false) {
            throw new ConstraintDefinitionException(
                sprintf(
                    'The object "%s" does not have a "%s" property.',
                    $reflection->getName(),
                    $constraint->end
                )
            );
        }

        $validPropertyPaths = array($constraint->start, $constraint->end);

        if ($constraint->errorOnProperty && !in_array($constraint->errorOnProperty, $validPropertyPaths)) {
            throw new ConstraintDefinitionException(
                sprintf(
                    'Cannot set error message on "%s". Choose from: %s',
                    $constraint->errorOnProperty,
                    json_encode($validPropertyPaths)
                )
            );
        }

        /*
         * Extract values from the class.
         */
        $startProp = $reflection->getProperty($constraint->start);
        $endProp = $reflection->getProperty($constraint->end);
        $startProp->setAccessible(true);
        $endProp->setAccessible(true);

        $start = $startProp->getValue($value);
        $end = $endProp->getValue($value);

        if (empty($start) || empty($end)) {
            return;
        }

        $start = $this->createDateTime($start);
        $end = $this->createDateTime($end);

        $diff = $start->diff($end);

        if ($diff->invert === 0) {
            return;
        }

        if ($constraint->errorOnProperty) {
            $message =
                $constraint->errorOnProperty === $constraint->end ? $constraint->endMessage : $constraint->startMessage;
            $limit =
                $constraint->errorOnProperty === $constraint->end ?
                    $start->format('Y-m-d') : $end->format('Y-m-d');
            $this
                ->context
                ->addViolationAt(
                    $constraint->errorOnProperty,
                    $message,
                    array(
                        '{{ limit }}' => $limit,
                    ),
                    null
                );
        } else {
            $this->context
                ->addViolation(
                    $constraint->invalidMessage,
                    array(
                        '{{ start }}' => $start->format('Y-m-d'),
                        '{{ end }}' => $end->format('Y-m-d'),
                    )
                );
        }

    }

    /**
     * @param $date
     *
     * @return \DateTime|null
     */
    private function createDateTime($date)
    {
        if ($date instanceof \DateTime) {
            return $date;
        } elseif (is_numeric($date)) {
            $d = new \DateTime();
            $d->setTimestamp($date);
            return $d;
        } elseif (is_string($date)) {
            return new \DateTime($date);
        }
    }

}
