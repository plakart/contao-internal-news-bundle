<?php

declare(strict_types=1);

namespace Plakart\InternalNewsBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Exception\AccessDeniedException;
use Contao\Input;
use Plakart\InternalNewsBundle\Category;
use Symfony\Bundle\SecurityBundle\Security;

// Runs on every table access, including direct URL calls.
#[AsCallback(table: Category::TABLE, target: 'config.onload')]
class AccessListener
{
    public function __construct(private readonly Security $security)
    {
    }

    public function __invoke(): void
    {
        $isAdmin = $this->security->isGranted('ROLE_ADMIN');

        if (Category::MODULE_EDIT === Input::get('do')) {
            if (!$isAdmin) {
                throw new AccessDeniedException('Das Modul "'.Category::MODULE_EDIT.'" ist nur für Administratoren erlaubt.');
            }

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
