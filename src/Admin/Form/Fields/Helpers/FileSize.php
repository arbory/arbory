<?php

declare(strict_types=1);

namespace Arbory\Base\Admin\Form\Fields\Helpers;

use Arbory\Base\Files\ArboryFile;

/**
 * Class FileSize.
 */
final class FileSize
{
    public const UNIT_BYTE = 'B';
    public const UNIT_KILOBYTE = 'KB';
    public const UNIT_MEGABYTE = 'MB';

    public const CLOSEST_UNIT_DIGIT_COUNT_DIFF = 3;

    public const UNITS = [
        self::UNIT_BYTE,
        self::UNIT_KILOBYTE,
        self::UNIT_MEGABYTE,
    ];

    /**
     * FileSize constructor.
     */
    public function __construct(private ArboryFile $file)
    {
    }

    public function getSizeInBytes(): int
    {
        return $this->file->getSize();
    }

    public function getReadableSize(): string
    {
        $sizeInBytes = $this->getSizeInBytes();
        $sizeLength = mb_strlen((string) $sizeInBytes);

        $unitIndex = (int) floor($sizeLength / self::CLOSEST_UNIT_DIGIT_COUNT_DIFF);
        $availableUnits = self::UNITS;
        $closestUnit = $availableUnits[$unitIndex] ?? end($availableUnits);

        $roundedSize = $this->roundToUnit($sizeInBytes, $closestUnit);

        return sprintf('%s %s', $roundedSize, $closestUnit);
    }

    private function roundToUnit(int $sizeInBytes, string $unit): float
    {
        $unitIndex = array_search($unit, self::UNITS, true);

        while ($unitIndex > 0) {
            $sizeInBytes /= 1000;
            $unitIndex--;
        }

        return round($sizeInBytes, 2);
    }
}
