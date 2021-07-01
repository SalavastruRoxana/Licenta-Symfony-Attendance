<?php


namespace App\Form;

use App\Models\UnivModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnivType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setAction($options['action'])
            ->setMethod($options['method'])
            ->add('numeleUniversitatii', TextType::class)
            ->add('numeleFacultatii', TextType::class)
            ->add('email', TextType::class)
            ->add('nrTel', TextType::class, array('label' => 'Numar Telefon'))
            ->add('adresa', TextType::class)
            ->add('Actualizare', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UnivModel::class,
        ]);
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }
}