<?php
/**
 * autop Twig Filter plugin for Craft CMS 3
 *
 * Strips html and generates paragraphs from new lines.
 *
 * @link      https://emandiev.com/
 * @copyright Copyright (c) 2020 Danail Emandiev
 */

namespace emandiev\autoptwigfilter\twigextensions;

use emandiev\autoptwigfilter\AutopTwigFilter;

use Craft;

/**
 * @author    Danail Emandiev
 * @package   AutopTwigFilter
 * @since     1.0.0
 */
class AutopTwigFilterTwigExtension extends \Twig_Extension
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'autopTwigFilter';
    }

    /**
     * @inheritdoc
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('autop', [$this, 'init'], ['is_safe' => ['all']]),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('autop', [$this, 'init'], ['is_safe' => ['all']]),
        ];
    }

    /**
     * @inheritdoc
     */
    public function init($text = '', $br = null)
    {
        if (empty($text) || ! is_string($text)) {
            return;
        }

        $default_settings = AutopTwigFilter::getInstance()->getSettings();
        $default_br = $default_settings['br'];

        if (! is_bool($default_settings['br'])) {
            return 'The "settings/autop-twig-filter.php" file exists but the "br" parameter has a non-boolean value. Please, set a boolean value.';
        }

        if (is_null($br)) {
            $br = $default_settings['br'];
        }

        return $this->autop($text, $br);
    }

    /**
     * @param string $text
     * @param bool $br
     *
     * @return string Formatted text
     */
    public function autop($text = '', $br = true)
    {
        $text = strip_tags($text);
        $text = trim($text);
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = preg_replace("~\n\n+~", "\n\n", $text);
        $text = '<p>' . implode('</p><p>', array_filter(explode("\n\n", $text))) . '</p>';
        $text = preg_replace('~<p>\s+</p>~', '', $text);

        if ($br) {
            $text = str_replace("\n", '<br>', $text);
            $text = preg_replace('~\s+<(/?(p|br))>~', '<$1>', $text);
            $text = preg_replace('~<(/?(p|br))>\s+~', '<$1>', $text);
            $text = preg_replace('~(<br>)+~', '<br>', $text);
            $text = str_replace(['<p><br>','<br></p>'], ['<p>', '</p>'], $text);
        } else {
            $text = preg_replace("~\n~", '', $text);
            $text = preg_replace('~\s+<(/?p)>~', '<$1>', $text);
            $text = preg_replace('~<(/?p)>\s+~', '<$1>', $text);
        }

        $text = preg_replace('~\s\s+~', ' ', $text);

        return $text;
    }
}
