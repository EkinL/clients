<?php

namespace App\Form;

use App\Entity\Deliverables;
use App\Entity\Projects;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class DeliverablesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('deliveryDate', null, [
                'widget' => 'single_text',
            ])
            ->add('status', EnumType::class, ['class' => 'App\Enum\DelivrerablesStatusEnum'])
            ->add('project', EntityType::class, [
                'class' => Projects::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Deliverables::class,
        ]);
    }
}
