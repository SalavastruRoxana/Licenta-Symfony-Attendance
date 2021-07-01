<?php


namespace App\Form;

use App\Models\StudentRegisterModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class StudentType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setAction($options['action'])
            ->setMethod($options['method'])
            ->add('lastName', TextType::class, ['label' => 'Nume'])
            ->add('firstName', TextType::class, ['label' => 'Prenume'])
            ->add('email', TextType::class, ['label' => 'Email'])
            ->add('type', ChoiceType::class, array(
                'choices' => array('Administrator' => 'ROLE_ADMIN',
                    'Professor' => 'ROLE_PROFESSOR',
                    'Student' => 'ROLE_STUDENT'),
                'expanded' => true,
                'data'=> 'ROLE_USER',
                'label' => 'Tipul de utilizator'
            ))
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Parola'],
                'second_options' => ['label' => 'Repeta Parola'],
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'save']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StudentRegisterModel::class,
            'csrf_protection' => false,
        ]);
    }
}