<?php

declare(strict_types=1);

namespace Sgv\InternalNewsBundle;

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
     * Kategorie => Contao-CSS-Variable für die Label-Farbe (hell/dunkel automatisch).
     */
    public const COLORS = [
        'security' => '--red',
        'hosting' => '--blue',
        'redaktionstool' => '--green',
        'recht' => '--orange',
        'web' => '--contao',
        'vertrag' => '--gray',
    ];

    /**
     * @return list<string>
     */
    public static function keys(): array
    {
        return array_keys(self::COLORS);
    }
}
