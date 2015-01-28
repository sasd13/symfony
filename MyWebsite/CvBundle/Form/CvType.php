<?php

namespace MyWebsite\CvBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use MyWebsite\WebBundle\Form\CategoryType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CvType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
            ->setMethod('POST')
			->add('title', 'text', array(
				'attr' => array(
					'placeholder' => 'example : Mon Cv',
				)
			))
			->add('disponibility', 'text', array(
				'required' => false,
				'attr' => array(
					'placeholder' => 'Janvier 2015',
				)
			))
			->add('mobility', 'text', array(
				'required' => false,
				'attr' => array(
					'placeholder' => 'Paris et environs',
				)
			))
			->add('description', 'textarea', array(
				'required' => false
			))
			->add('categories', 'collection', array(
				'type' => new CategoryType()
			))
        ;
		
		$builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
			$data = $event->getData();
			$form = $event->getForm();
			
			if($data->getActive() === false)
			{
				$form->add('active', 'button', array(
					'attr' => array(
						'value' => 'Activer',
					)
				));
			}
			else
			{
				$form->add('active', 'button', array(
					'attr' => array(
						'value' => 'Désactiver',
					)
				));
			}
		});
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyWebsite\CvBundle\Entity\Cv',
			'cascade_validation' => true,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cv_cv';
    }
}
