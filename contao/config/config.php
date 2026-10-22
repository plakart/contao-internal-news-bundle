<?php

use Contao\System;
use Plakart\InternalNewsBundle\Category;
use Symfony\Component\HttpFoundation\Request;

// disablePermissionChecks keeps the module out of user/group module lists; admin-only is enforced in AccessListener.
$GLOBALS['BE_MOD']['system'][Category::MODULE_EDIT] = [
    'tables' => [Category::TABLE],
    'disablePermissionChecks' => true,
];

// Read-only list for all back end users, linked from the dashboard.
$GLOBALS['BE_MOD']['system'][Category::MODULE_LIST] = [
    'tables' => [Category::TABLE],
    'hideInNavigation' => true,
    'disablePermissionChecks' => true,
];

$container = System::getContainer();

if ($container->get('contao.routing.scope_matcher')->isBackendRequest($container->get('request_stack')->getCurrentRequest() ?? Request::create(''))) {
    $GLOBALS['TL_CSS'][] = 'bundles/plakartinternalnews/backend.css|static';
}
