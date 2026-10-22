<?php

declare(strict_types=1);

namespace Plakart\InternalNewsBundle;

/**
 * Feste Kategorie-Liste. Die Labels stehen in der Sprachdatei
 * ($GLOBALS['TL_LANG']['tl_sgv_internal_news']['categories']).
 */
final class Category
{
    public const TABLE = 'tl_sgv_internal_news';

    public const MODULE_EDIT = 'sgv_internal_news';

    public const MODULE_LIST = 'sgv_internal_news_list';

    /**
     * Kategorie => CSS-Farbe für das Label. Contao-Variablen passen sich Hell/Dunkel automatisch an.
     */
    public const COLORS = [
        'security' => 'var(--red)',
        'hosting' => 'var(--blue)',
        'redaktionstool' => 'var(--green)',
        'recht' => 'var(--orange)',
        'web' => 'var(--contao)',
        'vertrag' => 'var(--gray)',
        'schulung' => '#8250c8',
    ];

    /**
     * @return list<string>
     */
    public static function keys(): array
    {
        return array_keys(self::COLORS);
    }
}
