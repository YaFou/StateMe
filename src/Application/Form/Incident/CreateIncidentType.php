<?php

namespace App\Application\Form\Incident;

use App\Domain\Incident\Dto\CreateIncidentDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateIncidentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message', TextareaType::class)
            ->add('createdAt', DateTimeType::class, ['input' => 'datetime_immutable'])
            ->add('serviceUpdates', ServiceUpdatesType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', CreateIncidentDto::class);
    }
}
