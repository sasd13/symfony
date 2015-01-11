<?php

namespace MyWebsite\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ContentBufferType extends AbstractType
{
	/**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
			->setMethod('POST')
			->add('required')
		;
		
		$builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
			$data = $event->getData();
			$form = $event->getForm();
			
			$formType = $data->getFormType();
			$required = $data->getRequired();
			
			if($formType === 'textarea')
			{
				$form->add('textValue', $formType, array(
					'required' => $required
				));
			}
			else
			{
				$form->add('stringValue', $formType, array(
					'required' => $required
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
            'data_class' => 'MyWebsite\WebBundle\Entity\ContentBuffer',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mywebsite_webbundle_contentBuffer';
    }
}
