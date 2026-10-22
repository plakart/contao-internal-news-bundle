<?php

declare(strict_types=1);

namespace Plakart\InternalNewsBundle\Dashboard;

use Contao\Config;
use Contao\CoreBundle\InsertTag\InsertTagParser;
use Contao\Date;
use Contao\StringUtil;
use Plakart\InternalNewsBundle\Category;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Erzeugt Label und Text-Markup, das im Dashboard und in der Backend-Liste gleich aussieht.
 * Es wird nur mit Contao-CSS-Variablen gefärbt, damit Hell/Dunkel ohne eigenes CSS passt.
 */
class NewsRenderer
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly InsertTagParser $insertTagParser,
    ) {
    }

    public function formatDate(int|string $timestamp): string
    {
        return Date::parse(Config::get('dateFormat'), (int) $timestamp);
    }

    public function categoryLabel(string $category): string
    {
        $id = 'categories.'.$category;
        $label = $this->translator->trans($id, [], 'contao_tl_sgv_internal_news');

        return $label === $id ? $category : $label;
    }

    public function badge(string $category): string
    {
        $color = Category::COLORS[$category] ?? 'var(--gray)';

        return \sprintf(
            '<span style="display:inline-block;padding:1px 8px;border:1px solid color-mix(in srgb,%1$s 60%%,transparent);border-radius:var(--border-radius,.375rem);background:color-mix(in srgb,%1$s 15%%,transparent);color:var(--text);font-size:.85em;line-height:1.5;white-space:nowrap">%2$s</span>',
            $color,
            StringUtil::specialchars($this->categoryLabel($category)),
        );
    }

    /**
     * Rich-Text ausgeben: erst Insert-Tags (Link-Picker) auflösen, dann bereinigen.
     */
    public function text(string $html): string
    {
        $html = StringUtil::decodeEntities($html);
        $html = $this->insertTagParser->replaceInline($html);

        $config = (new HtmlSanitizerConfig())
            ->allowElement('a', ['href', 'title'])
            ->allowElement('strong')
            ->allowElement('b')
            ->allowElement('em')
            ->allowElement('i')
            ->allowElement('br')
            ->allowElement('p')
            ->allowLinkSchemes(['http', 'https', 'mailto'])
            ->allowRelativeLinks()
            ->forceAttribute('a', 'rel', 'noopener')
        ;

        return (new HtmlSanitizer($config))->sanitize($html);
    }
}
