<?php

namespace MyWebsite\ProfileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use MyWebsite\WebBundle\Form\CategoryType;

class ClientType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
            ->setMethod('POST')
			->add('login', 'text', array(
				'attr' => array(
					'placeholder' => 'example : ab001',
				)
			))
			->add('password', 'password', array(
				'attr' => array(
					'placeholder' => 'password',
				)
			))
			->add('firstName', 'text', array(
				'attr' => array(
					'placeholder' => 'your first name',
				)
			))
			->add('lastName', 'text', array(
				'attr' => array(
					'placeholder' => 'your last name',
				)
			))
			->add('email', 'email', array(
				'attr' => array(
					'placeholder' => 'example@email.com',
				)
			))
			->add('categories', 'collection', array(
				'type' => new CategoryType()
			))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyWebsite\ProfileBundle\Entity\Client',
			'cascade_validation' => true,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'profile_client';
    }
}
