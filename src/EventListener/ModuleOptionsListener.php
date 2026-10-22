<?php

declare(strict_types=1);

namespace Sgv\InternalNewsBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Sgv\InternalNewsBundle\Category;

/**
 * Entfernt das Pflege-Modul aus der Modul-Auswahl von Benutzern und Benutzergruppen,
 * damit es dort gar nicht erst angeboten wird (analog zu disablePermissionChecks-Modulen).
 */
#[AsCallback(table: 'tl_user', target: 'fields.modules.options', priority: 100)]
#[AsCallback(table: 'tl_user_group', target: 'fields.modules.options', priority: 100)]
class ModuleOptionsListener
{
    /**
     * @return array<string, list<string>>
     */
    public function __invoke(): array
    {
        $modules = [];

        foreach ($GLOBALS['BE_MOD'] as $group => $items) {
            if (empty($items)) {
                continue;
            }

            foreach ($items as $name => $config) {
                if (true === ($config['disablePermissionChecks'] ?? false) || Category::MODULE_EDIT === $name) {
                    unset($items[$name]);
                }
            }

            $modules[$group] = array_keys($items);
        }

        return $modules;
    }
}
