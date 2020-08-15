<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\HubSpot\Repository\Form\Type;

use EzPlatform\HubSpot\HubSpot\Repository\Form\Data\DateBasedHubspotOptionsData;
use EzSystems\EzPlatformAdminUi\Form\Type\Content\LocationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;

/**
 * Class DateBasedHubspotOptionsDataType
 * @package EzPlatform\HubSpot\HubSpot\Repository\Form\Type
 */
class DateBasedHubspotOptionsDataType extends AbstractType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('schedulePublishingOption', ChoiceType::class, [
            'choices'  => [
                'Not yet' => 'not_yet',
                'Now' => 'now',
                'Draft' => 'draft',
                'Later' => 'later'
            ],
            'data' => 'not_yet',
            'expanded'  => true,
            'choice_attr' => function ($val, $key, $index) {
                return ['class' => 'field_'.$val];
            },
            'attr' => [
                'class' => 'options_hubspot__input',
            ]
        ])
        ->add('flatpickr', TextType::class, [
            'label' => /** @Desc("Share later") */ 'hubspot.share.later',
            'attr' => [
                'class' => 'ez-picker__input form-control flatpickr-input active',
                'readonly'  => 'readonly',
                'data-flatpickr-config' => '{ "weekNumbers": true, "minDate": "today", "defaultHour":"'.date("H").'", "defaultMinute":"'.date("i").'", "minTime": "'.date("H:i").'"  }'
            ],
        ])
        ->add('timestamp', HiddenType::class, [
            'constraints' => [
                new Positive(),
            ],
        ])
        ->add('location', LocationType::class)
        ->add(
            'apiKeyRecordData',
            DateBasedHubspotOptionsChannelsType::class,
            [
            'data'  => [
                'apiChannelsData'  => $options['data']->apiChannelsData
            ]
        ]
        );
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DateBasedHubspotOptionsData::class,
            'translation_domain' => 'hubspot',
        ]);
    }
}
