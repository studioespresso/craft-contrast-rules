# Color Contrast Rules

A Craft CMS plugin that automatically validates color fields against WCAG 2.1 accessibility contrast ratio requirements.

## Requirements

- Craft CMS 5.5.0 or later
- PHP 8.2 or later

## Installation

Install the plugin via Composer:

```bash
composer require studioespresso/contrastrules
```

Then install the plugin in the Craft Control Panel under **Settings** → **Plugins**, or via the command line:

```bash
php craft plugin/install _color-contrast-rules
```

## How It Works

The plugin automatically validates color fields in your Craft entries to ensure they meet WCAG accessibility standards. When an editor selects a color that doesn't provide sufficient contrast, they'll receive a validation error with the actual contrast ratio.

### WCAG Compliance Levels

- **AA Level**: Minimum contrast ratio of 4.5:1 (standard compliance)
- **AAA Level**: Minimum contrast ratio of 7:1 (enhanced compliance)

## Configuration

Currently, the plugin is configured to validate specific color fields. In the current implementation:

- Field handle: `colorFieldWithValidator`
- Text color: Black (`#000`)
- WCAG Level: AAA (7:1 ratio required)

The validation runs automatically when saving entries with color fields.

### Customizing Validation Rules

To add validation for multiple color fields or customize the validation rules, you can modify the `ColorContrastRules::define()` method in `src/rules/ColorContrastRules.php`:

```php
public static function define(): array
{
    return [
        // Background color field - test against white text
        [
            'field:backgroundColor',
            ContrastValidator::class,
            'textColor' => '#ffffff',
            'wcagLevel' => 'AA',
            'message' => 'Background color must have sufficient contrast against white text. Current ratio: {ratio}:1',
        ],
        
        // Button color field - test against black text  
        [
            'field:buttonColor',
            ContrastValidator::class,
            'textColor' => '#000000',
            'wcagLevel' => 'AAA',
            'message' => 'Button color must have high contrast against black text. Current ratio: {ratio}:1',
        ],
    ];
}
```

**Configuration Options:**
- `field:fieldHandle` - The color field to validate
- `textColor` - Hex color to test contrast against (`#ffffff`, `#000000`, etc.)
- `wcagLevel` - Compliance level (`'AA'` for 4.5:1, `'AAA'` for 7:1)
- `message` - Custom error message (optional, `{ratio}` placeholder available)

## Example

When a color fails validation, editors will see an error message like:

> "Color does not meet WCAG AAA contrast requirements (7.0:1) against white text. Current ratio: 3.2:1"

This helps content creators choose accessible color combinations that work for all users, including those with visual impairments.

## About WCAG Contrast Ratios

The Web Content Accessibility Guidelines (WCAG) define contrast ratios to ensure text is readable against background colors:

- **3:1** - Minimum for large text (AA Large)
- **4.5:1** - Standard for normal text (AA)
- **7:1** - Enhanced contrast for normal text (AAA)

The plugin uses the official WCAG formula for calculating relative luminance and contrast ratios.

---

Brought to you by [Studio Espresso](https://www.studioespresso.co/)