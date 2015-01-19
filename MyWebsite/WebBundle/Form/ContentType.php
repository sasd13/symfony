<?php

namespace MyWebsite\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ContentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
			->setMethod('POST')
			->add('label')
            ->add('labelValue')
			->add('formType')
            ->add('required')
            ->add('policyLevel')
            ->add('placeholder')
        ;
		
		$builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
			$data = $event->getData();
			$form = $event->getForm();
			
			if($data->getFormType() === 'textarea')
			{
				$form->add('textValue', $data->getFormType(), array(
					'required' => $data->getRequired()
				));
			}
			else
			{
				$form->add('stringValue', $data->getFormType(), array(
					'required' => $data->getRequired()
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
            'data_class' => 'MyWebsite\WebBundle\Entity\Content'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mywebsite_webbundle_content';
    }
}
