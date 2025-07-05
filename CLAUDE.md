# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with the Color Contrast Rules plugin.

## Plugin Overview

**Color Contrast Rules** is a Craft CMS plugin that automatically validates color fields against WCAG 2.1 accessibility contrast ratio requirements. It ensures content creators choose accessible color combinations.

## Architecture

### Core Components

**Main Plugin Class** (`src/ContrastRules.php`)
- Entry point and event handler registration
- Hooks into Craft's `EVENT_DEFINE_RULES` on Entry elements
- Registers validation rules for color fields

**Validation Rules** (`src/rules/ColorContrastRules.php`)
- Defines validation rules for specific color fields
- Currently targets field handle `colorFieldWithValidator`
- Configures WCAG level (AAA), text color (#000), and error messages

**Contrast Validator** (`src/validators/ContrastValidator.php`)
- Core WCAG 2.1 contrast ratio calculation logic
- Validates `ColorData` objects from Craft color fields
- Implements proper scientific luminance calculations
- Supports AA (4.5:1) and AAA (7:1) compliance levels

**Settings Model** (`src/models/Settings.php`)
- Currently empty but ready for future configuration options
- Standard Craft plugin settings structure

### Technical Implementation

**WCAG Calculation Process:**
1. Converts hex colors to RGB values
2. Applies sRGB color space conversion
3. Calculates relative luminance using WCAG formula: `0.2126 * R + 0.7152 * G + 0.0722 * B`
4. Computes contrast ratio: `(lighter + 0.05) / (darker + 0.05)`

**Validation Flow:**
1. Entry save event triggered
2. `ColorContrastRules::define()` adds validation rules
3. `ContrastValidator` checks color field values
4. Validation error shown if contrast ratio insufficient

## Current Configuration

The plugin is currently hard-coded with these settings:
- **Target Field**: `colorFieldWithValidator`
- **Text Color**: Black (`#000`)
- **WCAG Level**: AAA (7:1 ratio required)
- **Element Type**: Entry only

## Known Limitations

1. **Hard-coded field names** - Rules tied to specific field handle
2. **Single element type** - Only validates Entry elements
3. **Fixed configuration** - No admin settings interface
4. **Limited text colors** - Only tests against one text color
5. **No field discovery** - Doesn't automatically find color fields

## Development Guidelines

### When Working on This Plugin:

**Code Style:**
- Follow PSR-4 autoloading: `studioespresso\contrastrules\`
- Use proper PHP 8.2+ syntax and type hints
- Follow Craft CMS coding standards

**Validation Rules:**
- Always use Craft's validation system (`EVENT_DEFINE_RULES`)
- Target fields using `field:fieldHandle` syntax
- Provide meaningful error messages with actual contrast ratios

**WCAG Compliance:**
- Use official WCAG 2.1 formula for luminance calculation
- Support both AA (4.5:1) and AAA (7:1) standards
- Include actual contrast ratio in error messages

### Potential Improvements:

1. **Dynamic field configuration** - Settings to select which color fields to validate
2. **Multi-element support** - Extend beyond Entry elements
3. **Configurable text colors** - Test against multiple text color scenarios
4. **Settings interface** - Admin panel for rule configuration
5. **Rule templates** - Pre-defined validation scenarios

### Testing Considerations:

- Test with various hex color formats (#fff, #ffffff, etc.)
- Verify WCAG calculations against official tools
- Test edge cases (pure black/white, similar colors)
- Validate error message formatting and translation

## File Structure

```
src/
├── ContrastRules.php           # Main plugin class
├── models/
│   └── Settings.php           # Plugin settings (empty)
├── rules/
│   └── ColorContrastRules.php # Validation rule definitions
├── templates/
│   └── _settings.twig         # Settings template (basic)
└── validators/
    └── ContrastValidator.php  # WCAG contrast validation logic
```

## Integration Points

**Craft CMS Integration:**
- Uses Craft's element validation system
- Works with native ColorData field type
- Follows standard plugin architecture patterns

**WCAG Standards:**
- Implements official WCAG 2.1 contrast algorithms
- Provides AA and AAA compliance levels
- Uses proper relative luminance calculations

## Important Notes

- The plugin automatically validates on element save
- Validation only applies to Entry elements currently
- Error messages include actual contrast ratios for user guidance
- Mathematical calculations follow WCAG 2.1 specification exactly
- Plugin is designed for accessibility compliance, not design preferences

## Dependencies

- Craft CMS 5.5.0+
- PHP 8.2+
- ColorData field type (built into Craft)

This plugin provides a solid foundation for WCAG color validation but would benefit from more flexible configuration options for production use.