<?php

declare(strict_types=1);

namespace Sgv\InternalNewsBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\StringUtil;
use Sgv\InternalNewsBundle\Category;
use Sgv\InternalNewsBundle\Dashboard\NewsRenderer;

#[AsCallback(table: Category::TABLE, target: 'list.label.label')]
class ListLabelListener
{
    public function __construct(private readonly NewsRenderer $renderer)
    {
    }

    /**
     * @param array<string, mixed> $row
     * @param array<int, string>   $labels
     *
     * @return array<int, string>
     */
    public function __invoke(array $row, string $label, mixed $dc, array $labels): array
    {
        $text = StringUtil::substr(trim(strip_tags(StringUtil::decodeEntities((string) $row['text']))), 160);

        return [
            $this->renderer->formatDate($row['date']),
            $this->renderer->badge((string) $row['category']),
            StringUtil::specialchars($text),
        ];
    }
}
