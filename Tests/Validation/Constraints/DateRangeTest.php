<?php


namespace ActiveLAMP\Bundle\UtilsBundle\Tests\Validation\Constraints;

use ActiveLAMP\Bundle\UtilsBundle\Tests\Fixtures\Entity\Event;
use ActiveLAMP\Bundle\UtilsBundle\Validation\Constraints\DateRange;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class DateRangeTest
 *
 * @author Bez Hermoso <bez@activelamp.com>
 */
class DateRangeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    public function setUp()
    {
        $builder =Validation::createValidatorBuilder();
        $builder->enableAnnotationMapping(new AnnotationReader());
        $this->validator = $builder->getValidator();
    }

    public function testOkCondition()
    {
        $event = new Event('2014-07-01', '2014-07-02');

        $errors = $this->validator->validate($event);
        $this->assertCount(0, $errors);

        $event = new Event(null, null);

        $errors = $this->validator->validate($event);
        $this->assertCount(0, $errors);


        $event = new Event(null, '2014-01-01');
        $errors = $this->validator->validate($event);
        $this->assertCount(0, $errors);

        $event = new Event('2013-01-01', null);
        $errors = $this->validator->validate($event);
        $this->assertCount(0, $errors);

    }

    public function testNotOkCondition()
    {
        $event = new Event('2014-07-10', '2014-06-01');

        $errors = $this->validator->validate($event);

        $this->assertCount(1, $errors);
        $this->assertEquals('Invalid date range', $errors[0]->getMessage());
        $this->assertEmpty($errors[0]->getPropertyPath());

        $meta = $this->validator->getMetadataFor(get_class($event));
        $constriants = $meta->findConstraints('Default');

        /** @var $constriants DateRange */
        $constriant = $constriants[0];

        $constriant->errorOnProperty = 'startDate';
        $errors = $this->validator->validate($event);

        $this->assertEquals('Start date should be less than or equal to 2014-06-01', $errors[0]->getMessage());
        $this->assertEquals('startDate', $errors[0]->getPropertyPath());

        $constriant->errorOnProperty = 'endDate';
        $errors = $this->validator->validate($event);

        $this->assertEquals('End date should be greater than or equal to 2014-07-10', $errors[0]->getMessage());
        $this->assertEquals('endDate', $errors[0]->getPropertyPath());

        $constriant->errorOnProperty = null;

    }
}
