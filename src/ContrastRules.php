<?php

namespace studioespresso\contrastrules;

use Craft;
use craft\base\Event;
use craft\base\Model;
use craft\base\Plugin;
use craft\elements\Entry;
use craft\events\DefineRulesEvent;
use studioespresso\contrastrules\models\Settings;
use studioespresso\contrastrules\rules\ColorContrastRules;

/**
 * Color Contrast Rules plugin
 *
 * @method static ContrastRules getInstance()
 * @method Settings getSettings()
 */
class ContrastRules extends Plugin
{
    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = true;
    public bool $hasCpSection = false;

    public static function config(): array
    {
        return [
            'components' => [
                // Define component configs here...
            ],
        ];
    }

    public function init(): void
    {
        parent::init();

        // Set the controllerNamespace based on whether this is a console or web request
        if (Craft::$app->request->isConsoleRequest) {
            $this->controllerNamespace = 'studioespresso\\contrastrules\\console\\controllers';
        }

        $this->attachEventHandlers();

        // Any code that creates an element query or loads Twig should be deferred until
        // after Craft is fully initialized, to avoid conflicts with other plugins/modules
        Craft::$app->onInit(function() {
            // ...
        });
    }

    protected function createSettingsModel(): ?Model
    {
        return Craft::createObject(Settings::class);
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->view->renderTemplate('_color-contrast-rules/_settings.twig', [
            'plugin' => $this,
            'settings' => $this->getSettings(),
        ]);
    }

    private function attachEventHandlers(): void
    {
        // Add WCAG contrast validation rules to elements using Craft's proper validation system
        Event::on(
            Entry::class,
            Entry::EVENT_DEFINE_RULES,
            function(DefineRulesEvent $event) {
                // Add standard AA level contrast validation rules
                foreach (ColorContrastRules::define() as $rule) {
                    $event->rules[] = $rule;
                }
            }
        );
    }
}
