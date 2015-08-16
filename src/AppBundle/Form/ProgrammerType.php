<?php
/**
 * Created by PhpStorm.
 * User: lanbn
 * Date: 7/19/2015
 * Time: 9:18 PM
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProgrammerType extends AbstractType
{
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'programmer';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nickname', 'text', [
                    // readonly if we're in edit mode
                    'disabled' => $options['is_edit']
                ])
                ->add('avatarNumber', 'choice', [
                    'choices' => [
                        // the key is the value that will be set
                        // the value/label isn't shown in an API, and could
 	                    // be set to anything
	                    1 => 'Girl (green)',
	                    2 => 'Boy',
	                    3 => 'Cat',
	                    4 => 'Boy with Hat',
	                    5 => 'Happy Robot',
	                    6 => 'Girl (purple)',
	                ]])
                ->add('tagLine', 'textarea');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Programmer',
            'is_edit' => false
        ));
    }

}