<?php

declare(strict_types=1);

namespace Plakart\InternalNewsBundle;

final class Category
{
    public const TABLE = 'tl_plakart_internal_news';

    public const MODULE_EDIT = 'plakart_internal_news';

    public const MODULE_LIST = 'plakart_internal_news_list';

    private const KEYS = [
        'security',
        'hosting',
        'redaktionstool',
        'recht',
        'web',
        'vertrag',
        'schulung',
    ];

    /**
     * @return list<string>
     */
    public static function keys(): array
    {
        return self::KEYS;
    }
}
