<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\Repository\Menu\Event;

/**
 * Class HubspotConfigureMenuEventName
 * @package EzPlatform\HubSpot\Repository\Menu\Event
 */
class HubspotConfigureMenuEventName
{
    const HUBSPOT_MENU_SETTINGS_SIDEBAR = 'hubspot.menu_configure.settings.sidebar_left';
    const HUBSPOT_MENU_SOCIAL_SIDEBAR = 'hubspot.menu_configure.social.sidebar_left';
    const HUBSPOT_MENU_API_SETTING_EDIT_SIDEBAR_RIGHT = 'hubspot.menu_configure.settings.sidebar_right_edit';
}
