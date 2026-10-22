<?php

declare(strict_types=1);

namespace Plakart\InternalNewsBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Doctrine\DBAL\Connection;
use Plakart\InternalNewsBundle\Category;
use Plakart\InternalNewsBundle\Dashboard\NewsRenderer;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Contao 5.7 hat keine Dashboard-Widgets. Die Startseite wird als Template "be_welcome"
 * gerendert; der offizielle Hook parseBackendTemplate erlaubt es, dort einen Abschnitt
 * einzufügen, ohne ein Core-Template zu überschreiben.
 */
#[AsHook('parseBackendTemplate')]
class DashboardListener
{
    private const LIMIT = 5;

    public function __construct(
        private readonly Connection $connection,
        private readonly NewsRenderer $renderer,
        private readonly RouterInterface $router,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function __invoke(string $buffer, string $template): string
    {
        if ('be_welcome' !== $template) {
            return $buffer;
        }

        $marker = '<div id="tl_soverview">';
        $position = strpos($buffer, $marker);

        if (false === $position) {
            return $buffer;
        }

        $position += \strlen($marker);

        return substr($buffer, 0, $position)."\n".$this->render().substr($buffer, $position);
    }

    private function render(): string
    {
        $rows = $this->connection->fetchAllAssociative(
            \sprintf('SELECT `date`, category, headline, `text` FROM %s ORDER BY `date` DESC, id DESC LIMIT %d', Category::TABLE, self::LIMIT),
        );

        $trans = fn (string $key): string => $this->translator->trans($key, [], 'contao_tl_sgv_internal_news');
        $html = '<div id="sgv_internal_news"><h2>'.htmlspecialchars($trans('dashboard.headline')).'</h2>';

        if ([] === $rows) {
            return $html.'<p>'.htmlspecialchars($trans('dashboard.empty')).'</p></div>';
        }

        $html .= '<ul style="list-style:none;margin:0;padding:0">';

        foreach ($rows as $row) {
            $html .= \sprintf(
                '<li style="padding:.6rem 0;border-bottom:1px solid var(--content-border)"><div style="display:flex;flex-wrap:wrap;gap:.5rem;align-items:center;margin-bottom:.25rem"><time style="color:var(--gray)">%s</time>%s</div><div><strong style="display:block">%s</strong>%s</div></li>',
                htmlspecialchars($this->renderer->formatDate($row['date'])),
                $this->renderer->badge((string) $row['category']),
                htmlspecialchars((string) $row['headline']),
                $this->renderer->text((string) $row['text']),
            );
        }

        $url = $this->router->generate('contao_backend', ['do' => Category::MODULE_LIST]);

        return $html.\sprintf('</ul><p style="margin-top:.75rem"><a href="%s">%s</a></p></div>', htmlspecialchars($url), htmlspecialchars($trans('dashboard.all')));
    }
}
