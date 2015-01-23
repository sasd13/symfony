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
			->add('login', 'text')
			->add('password', 'password')
			->add('email', 'email')
			->add('firstName', 'text')
			->add('lastName', 'text')
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
        return 'mywebsite_profilebundle_client';
    }
}
