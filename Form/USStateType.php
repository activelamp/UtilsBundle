<?php


namespace ActiveLAMP\Bundle\UtilsBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class USStateType
 *
 * @author Bez Hermoso <bez@activelamp.com>
 */
class USStateType extends ChoiceType
{
    protected $states;

    /**
     * @param array $states Array of US states
     */
    public function __construct(array $states)
    {
        $this->states = $states;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'choices' => $this->states,
        ));
    }

    public function getParent()
    {
        return 'choice';
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'al_us_state_choice';
    }
}
