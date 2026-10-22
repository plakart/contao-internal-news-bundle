<?php

declare(strict_types=1);

namespace Plakart\InternalNewsBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Doctrine\DBAL\Connection;
use Plakart\InternalNewsBundle\Category;
use Plakart\InternalNewsBundle\Dashboard\NewsRenderer;
use Symfony\Component\Routing\RouterInterface;

// Contao 5.7 has no dashboard widget API, so the panel is injected into be_welcome, above the version list.
#[AsHook('parseBackendTemplate')]
class DashboardListener
{
    private const LIMIT = 5;

    public function __construct(
        private readonly Connection $connection,
        private readonly NewsRenderer $renderer,
        private readonly RouterInterface $router,
    ) {
    }

    public function __invoke(string $buffer, string $template): string
    {
        if ('be_welcome' !== $template) {
            return $buffer;
        }

        $position = strpos($buffer, '<div id="tl_versions">');

        if (false === $position) {
            return $buffer;
        }

        return substr($buffer, 0, $position).$this->render()."\n".substr($buffer, $position);
    }

    private function render(): string
    {
        $rows = $this->connection->fetchAllAssociative(
            \sprintf("SELECT `date`, category, headline, `text` FROM %s WHERE (start = '' OR start <= :now) AND (stop = '' OR stop > :now) ORDER BY `date` DESC, id DESC LIMIT %d", Category::TABLE, self::LIMIT),
            ['now' => time()],
        );

        $lang = $this->renderer->lang()['dashboard'];
        $html = '<div class="plakart-news"><h2>'.htmlspecialchars($lang['headline']).'</h2>';

        if ([] === $rows) {
            return $html.'<p>'.htmlspecialchars($lang['empty']).'</p></div>';
        }

        $html .= \sprintf(
            '<table class="plakart-news__table"><thead><tr><th>%s</th><th>%s</th><th>%s</th></tr></thead><tbody>',
            htmlspecialchars($lang['date']),
            htmlspecialchars($lang['category']),
            htmlspecialchars($lang['entry']),
        );

        foreach ($rows as $row) {
            $html .= \sprintf(
                '<tr><td class="plakart-news__date">%s</td><td class="plakart-news__category">%s</td><td><div class="plakart-news__headline">%s</div><div class="plakart-news__text">%s</div></td></tr>',
                htmlspecialchars($this->renderer->formatDate($row['date'])),
                $this->renderer->badge((string) $row['category']),
                htmlspecialchars((string) $row['headline']),
                $this->renderer->text((string) $row['text']),
            );
        }

        $url = $this->router->generate('contao_backend', ['do' => Category::MODULE_LIST]);

        return $html.\sprintf('</tbody></table><p class="plakart-news__all"><a href="%s">%s</a></p></div>', htmlspecialchars($url), htmlspecialchars($lang['all']));
    }
}
