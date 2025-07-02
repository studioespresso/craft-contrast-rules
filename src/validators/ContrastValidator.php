<?php

namespace studioespresso\contrastrules\validators;

use craft\fields\data\ColorData;
use yii\validators\Validator;

/**
 * WCAG Contrast Validator
 *
 * Validates color contrast ratios according to WCAG 2.1 guidelines
 */
class ContrastValidator extends Validator
{
    /**
     * @var string The text color to test contrast against
     */
    public string $textColor = '#ffffff';

    /**
     * @var string WCAG compliance level: 'AA' (4.5:1) or 'AAA' (7:1)
     */
    public string $wcagLevel = 'AA';

    /**
     * @var array WCAG contrast ratio requirements
     */
    private array $contrastRatios = [
        'AA' => 4.5,
        'AAA' => 7.0,
    ];

    /**
     * @inheritdoc
     */
    public function validateValue($value)
    {
        if (!$value instanceof ColorData) {
            return null;
        }

        $backgroundHex = $value->getHex();
        $requiredRatio = $this->contrastRatios[$this->wcagLevel] ?? 4.5;


        $ratio = $this->calculateContrastRatio($backgroundHex, $this->textColor);

        if ($ratio < $requiredRatio) {
            $textColorName = $this->textColor === '#ffffff' ? 'white' : ($this->textColor === '#000000' ? 'black' : $this->textColor);
            return [
                $this->message ?: "Color does not meet WCAG {$this->wcagLevel} contrast requirements ({$requiredRatio}:1) against {$textColorName} text. Current ratio: {ratio}:1",
                [
                    'ratio' => round($ratio, 2),
                    'textColor' => $textColorName,
                    'required' => $requiredRatio,
                    'level' => $this->wcagLevel,
                ],
            ];
        }


        return null;
    }

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute): void
    {
        $value = $model->$attribute;
        $result = $this->validateValue($value);

        if ($result !== null) {
            $this->addError($model, $attribute, $result[0], $result[1]);
        }
    }

    /**
     * Calculate WCAG contrast ratio between two colors
     *
     * @param string $color1 Hex color (e.g., '#ffffff')
     * @param string $color2 Hex color (e.g., '#000000')
     * @return float Contrast ratio
     */
    private function calculateContrastRatio(string $color1, string $color2): float
    {
        $luminance1 = $this->getRelativeLuminance($color1);
        $luminance2 = $this->getRelativeLuminance($color2);

        // Ensure the lighter color is the numerator
        $lighter = max($luminance1, $luminance2);
        $darker = min($luminance1, $luminance2);

        return ($lighter + 0.05) / ($darker + 0.05);
    }

    /**
     * Calculate relative luminance according to WCAG 2.1 specification
     *
     * @param string $hex Hex color (e.g., '#ffffff')
     * @return float Relative luminance (0-1)
     */
    private function getRelativeLuminance(string $hex): float
    {
        // Convert hex to RGB
        $rgb = $this->hexToRgb($hex);

        // Convert to sRGB
        $srgb = [];
        foreach ($rgb as $value) {
            $value = $value / 255;
            $srgb[] = $value <= 0.03928
                ? $value / 12.92
                : pow(($value + 0.055) / 1.055, 2.4);
        }

        // Calculate relative luminance
        return 0.2126 * $srgb[0] + 0.7152 * $srgb[1] + 0.0722 * $srgb[2];
    }

    /**
     * Convert hex color to RGB array
     *
     * @param string $hex Hex color (e.g., '#ffffff')
     * @return array RGB values [r, g, b]
     */
    private function hexToRgb(string $hex): array
    {
        // Remove # if present
        $hex = ltrim($hex, '#');

        // Handle 3-digit hex
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }
}
