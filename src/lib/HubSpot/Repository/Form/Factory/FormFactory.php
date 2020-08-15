<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\HubSpot\Repository\Form\Factory;

use eZ\Publish\API\Repository\Values\Content\Location;
use EzPlatform\HubSpot\HubSpot\Repository\Form\Data\DateBasedHubspotOptionsData;
use EzPlatform\HubSpot\HubSpot\Repository\Form\Type\ApiKeyFormType;
use EzPlatform\HubSpot\HubSpot\Repository\Form\Type\DateBasedHubspotOptionsDataType;
use EzPlatform\HubSpotBundle\Entity\HubSpot\ApiKey;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Util\StringUtil;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FormFactory.
 */
class FormFactory
{
    /** @var FormFactoryInterface $formFactory */
    protected $formFactory;

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        FormFactoryInterface $formFactory
    ) {
        $this->formFactory = $formFactory;
    }

    /**
     * @param \EzPlatform\HubSpotBundle\Entity\HubSpot\ApiKey $data
     * @param string|null $action
     * @param string|null $name
     * @return \Symfony\Component\Form\FormInterface|null
     */
    public function apiKeyFormType(
        ApiKey $data,
        string $action = null,
        string $name = null
    ): ?FormInterface {
        $name = $name ?: StringUtil::fqcnToBlockPrefix(ApiKeyFormType::class);

        return $this->formFactory->createNamed(
            $name,
            ApiKeyFormType::class,
            $data,
            [
                'action' => $action,
                'method' => Request::METHOD_POST,
            ]
        );
    }

    /**
     * Fom used in the content right sidebar to create message in hubspot
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Location $location
     * @return \Symfony\Component\Form\FormView
     */
    public function createContentFormView(
        Location $location,
        array $apiChannelsData = null
    ): FormView {
        $fqcnToBlockPrefix = StringUtil::fqcnToBlockPrefix(DateBasedHubspotOptionsDataType::class);


        $dateBasedHubspotData = new DateBasedHubspotOptionsData(
            $location,
            $apiChannelsData
        );

        return $this->formFactory->createNamed(
            $fqcnToBlockPrefix,
            DateBasedHubspotOptionsDataType::class,
            $dateBasedHubspotData
        )->createView();
    }
}
