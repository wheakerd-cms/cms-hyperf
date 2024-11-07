<?php
declare(strict_types=1);

namespace App\Utils;

final class HelperFunctions
{
    static public function listToTree(
        array  &$list,
        string $pk = 'id',
        string $pid = 'pid',
        string $child = 'children',
        int    $root = 0,
    ): array
    {
        $tree = [];

        foreach ($list as $item) {
            if ($item [$pid] === $root) {
                $children = self::listToTree($list, $pk, $pid, $child, $item [$pk]);
                $tree [] = empty($children) ? $item : $item + [$child => $children];
                unset($item);
            }
        }

        return $tree;
    }
}