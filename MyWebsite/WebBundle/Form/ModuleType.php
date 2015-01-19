<?php

namespace MyWebsite\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ModuleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
			->setMethod('POST')
            ->add('name', 'text')
			->add('active', 'checkbox')
            ->add('readMe', 'textarea')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyWebsite\WebBundle\Entity\Module'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mywebsite_webbundle_module';
    }
}
