<?php

declare(strict_types=1);

namespace Plakart\InternalNewsBundle\EventListener;

use Contao\CoreBundle\Event\ContaoCoreEvents;
use Contao\CoreBundle\Event\MenuEvent;
use Plakart\InternalNewsBundle\Category;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: ContaoCoreEvents::BACKEND_MENU_BUILD, priority: 0)]
class MenuListener
{
    public function __construct(private readonly Security $security)
    {
    }

    public function __invoke(MenuEvent $event): void
    {
        $tree = $event->getTree();

        if ('mainMenu' !== $tree->getName() || $this->security->isGranted('ROLE_ADMIN')) {
            return;
        }

        $tree->getChild('system')?->removeChild(Category::MODULE_EDIT);
    }
}
