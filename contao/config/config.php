<?php

use Plakart\InternalNewsBundle\Category;

// Pflege: "disablePermissionChecks" nimmt das Modul aus der Modul-Auswahl von Benutzern und
// Gruppen, es lässt sich also nicht über Gruppen freischalten. Der Admin-Check passiert
// serverseitig im onload_callback (AccessListener), das Menü-Item blendet MenuListener aus.
$GLOBALS['BE_MOD']['system'][Category::MODULE_EDIT] = [
    'tables' => [Category::TABLE],
    'disablePermissionChecks' => true,
];

// Schreibgeschützte Gesamtliste für alle Backend-Nutzer (Link vom Dashboard)
$GLOBALS['BE_MOD']['system'][Category::MODULE_LIST] = [
    'tables' => [Category::TABLE],
    'hideInNavigation' => true,
    'disablePermissionChecks' => true,
];
