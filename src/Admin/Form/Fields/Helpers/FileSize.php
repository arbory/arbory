<?php

declare(strict_types=1);

namespace Arbory\Base\Admin\Form\Fields\Helpers;

use Arbory\Base\Files\ArboryFile;

/**
 * Class FileSize.
 */
final class FileSize
{
    const UNIT_BYTE = 'B';
    const UNIT_KILOBYTE = 'KB';
    const UNIT_MEGABYTE = 'MB';

    const CLOSEST_UNIT_DIGIT_COUNT_DIFF = 3;

    const UNITS = [
        self::UNIT_BYTE,
        self::UNIT_KILOBYTE,
        self::UNIT_MEGABYTE,
    ];

    /**
     * @var ArboryFile
     */
    private $file;

    /**
     * FileSize constructor.
     * @param ArboryFile $file
     */
    public function __construct(ArboryFile $file)
    {
        $this->file = $file;
    }

    /**
     * @return int
     */
    public function getSizeInBytes(): int
    {
        return $this->file->getSize();
    }

    /**
     * @return string
     */
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

    /**
     * @param int $sizeInBytes
     * @param string $unit
     * @return float
     */
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
