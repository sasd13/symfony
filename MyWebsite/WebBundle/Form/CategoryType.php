<?php

namespace MyWebsite\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CategoryType extends AbstractType
{
	private $postSetContents;
	private $postSubmitContents;
	
	/**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
			->setMethod('POST')
            ->add('title', 'text')
            ->add('tag', 'text')
            ->add('type', 'choice')
			->add('contents', 'collection', array('type' => new ContentType()));
        ;
		
		$builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event) {
			$this->postSetContents = array_merge($event->getForm()->getData()->getContents()->toArray());
			
		});
		
		$builder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) {
			$this->postSubmitContents = (array) $event->getForm()->getData()->getContents();
			die(var_dump($this->postSetContents));
			//die(var_dump($this->postSubmitContents));
		});
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyWebsite\WebBundle\Entity\Category',
			'cascade_validation' => true,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mywebsite_webbundle_category';
    }
}
