<?php

declare(strict_types=1);

namespace Sgv\InternalNewsBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\Input;
use Sgv\InternalNewsBundle\Category;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * Die Gesamtliste (Modul sgv_internal_news_list) ist für alle schreibgeschützt.
 * Die eigentliche Sperre sind das Modul-Recht und der AdminOnlyVoter; das hier
 * blendet nur die Bearbeiten-Funktionen aus.
 */
#[AsCallback(table: Category::TABLE, target: 'config.onload')]
class ReadOnlyListener
{
    public function __construct(private readonly Security $security)
    {
    }

    public function __invoke(): void
    {
        $readOnly = Category::MODULE_EDIT !== Input::get('do') || !$this->security->isGranted('ROLE_ADMIN');

        if (!$readOnly) {
            return;
        }

        $dca = &$GLOBALS['TL_DCA'][Category::TABLE];
        $dca['config']['notEditable'] = true;
        $dca['config']['notDeletable'] = true;
        $dca['config']['notCreatable'] = true;
        $dca['config']['notCopyable'] = true;

        unset(
            $dca['list']['global_operations']['all'],
            $dca['list']['operations']['edit'],
            $dca['list']['operations']['delete'],
        );
    }
}
