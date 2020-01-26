<?php
/**
 * autop Twig Filter plugin for Craft CMS 3.x
 *
 * Strips html and generates paragraphs from new lines.
 *
 * @link      https://emandiev.com/
 * @copyright Copyright (c) 2020 Danail Emandiev
 */

namespace emandiev\autoptwigfilter;

use emandiev\autoptwigfilter\twigextensions\AutopTwigFilterTwigExtension;
use emandiev\autoptwigfilter\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;

use yii\base\Event;

/**
 * Class AutopTwigFilter
 *
 * @author    Danail Emandiev
 * @package   AutopTwigFilter
 * @since     1.0.0
 *
 */
class AutopTwigFilter extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var AutopTwigFilter
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Craft::$app->view->registerTwigExtension(new AutopTwigFilterTwigExtension());

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'autop-twig-filter',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    protected function createSettingsModel()
    {
        return new Settings();
    }
}
