<?php

use Plakart\InternalNewsBundle\Category;

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
