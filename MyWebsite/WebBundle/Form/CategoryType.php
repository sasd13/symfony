<?php

namespace MyWebsite\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
            ->add('title', 'text', array(
				'attr' => array(
					'placeholder' => 'exemple : Identity',
				)
			))
            ->add('tag', 'text', array(
				'attr' => array(
					'placeholder' => 'exemple : tag_identity',
				)
			))
            ->add('type', 'choice')
			->add('contents', 'collection', array(
				'type' => new ContentType()
			))
        ;
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
        return 'web_category';
    }
}
