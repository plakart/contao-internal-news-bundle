<?php

declare(strict_types=1);

namespace Plakart\InternalNewsBundle\Dashboard;

use Contao\Config;
use Contao\CoreBundle\InsertTag\InsertTagParser;
use Contao\Date;
use Contao\StringUtil;
use Contao\System;
use Plakart\InternalNewsBundle\Category;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class NewsRenderer
{
    public function __construct(
        private readonly InsertTagParser $insertTagParser,
    ) {
    }

    public function formatDate(int|string $timestamp): string
    {
        return Date::parse(Config::get('dateFormat'), (int) $timestamp);
    }

    public function categoryLabel(string $category): string
    {
        return $this->lang()['categories'][$category] ?? $category;
    }

    /**
     * @return array<string, mixed>
     */
    public function lang(): array
    {
        System::loadLanguageFile(Category::TABLE, 'de');

        return $GLOBALS['TL_LANG'][Category::TABLE];
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

    public function text(string $html): string
    {
        $html = StringUtil::decodeEntities($html);
        // Resolve insert tags (link picker) before sanitizing.
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
