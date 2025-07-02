<?php

namespace studioespresso\contrastrules\rules;

use Craft;
use studioespresso\contrastrules\validators\ContrastValidator;

/**
 * WCAG Color Contrast Validation Rules
 *
 * Defines validation rules for color fields to ensure WCAG compliance
 */
class ColorContrastRules
{
    /**
     * Define validation rules for color contrast
     *
     * @return array
     */
    public static function define(): array
    {
        return [
            // Test field for validation testing - test against white text
            [
                'field:colorFieldWithValidator',
                ContrastValidator::class,
                'textColor' => '#000',
                'wcagLevel' => 'AAA',
                'message' => Craft::t('site', 'Color does not meet WCAG AAA contrast requirements (7.0:1) against white text. Current ratio: {ratio}:1'),
            ],
        ];
    }
}
