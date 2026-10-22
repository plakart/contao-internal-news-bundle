<?php

use Sgv\InternalNewsBundle\Category;

// Pflege (nur Admins, per AdminOnlyVoter erzwungen)
$GLOBALS['BE_MOD']['system'][Category::MODULE_EDIT] = [
    'tables' => [Category::TABLE],
];

// Schreibgeschützte Gesamtliste für alle Backend-Nutzer (Link vom Dashboard)
$GLOBALS['BE_MOD']['system'][Category::MODULE_LIST] = [
    'tables' => [Category::TABLE],
    'hideInNavigation' => true,
    'disablePermissionChecks' => true,
];
