<?php

declare(strict_types=1);

namespace Plakart\InternalNewsBundle;

final class Category
{
    public const TABLE = 'tl_plakart_internal_news';

    public const MODULE_EDIT = 'plakart_internal_news';

    public const MODULE_LIST = 'plakart_internal_news_list';

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
