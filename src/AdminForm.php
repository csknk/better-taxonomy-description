<?php
/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace PluginBoilerplate;

/**
 * Adds plugin scripts and scripts data.
 *
 * @package PluginBoilerplate
 */
class AdminForm
{
    /**
     * @var \PluginBoilerplate\FormData
     */
    private $formData;

    /**
     * @param \PluginBoilerplate\FormData $formData
     */
    public function __construct(FormData $formData)
    {
        $this->formData = $formData;
    }

    /**
     * Adds PluginBoilerplate button to TinyMCE toolbar and connect it with javascript file.
     * Runs on 'admin_init' hook (se in main plugin file).
     */
    public function setup()
    {
        add_action('admin_head', function () {
            echo "
              <style>
              i.mce-i-plugin_boilerplate { font: 400 20px/1 dashicons; background-color: #777; }
              i.mce-i-plugin_boilerplate:before { color: #fff!important; }
              </style>
            ";
        });
        add_action('admin_enqueue_scripts', function () {
            wp_localize_script('editor', 'PluginBoilerplate', $this->formData->data());
        });
        add_filter('mce_buttons', function (array $buttons) {
            return array_merge($buttons, ['plugin_boilerplateRender']);
        });
        add_filter('mce_external_plugins', function (array $plugins) {
            return array_merge($plugins, ['plugin_boilerplateRender' => $this->scriptUrl()]);
        });
    }

    private function scriptUrl()
    {
        $name = defined('WP_DEBUG') && WP_DEBUG ? "/js/plugin_boilerplate.js" : "/js/plugin_boilerplate.min.js";

        return plugins_url($name, dirname(__DIR__).'/PluginBoilerplate.php');
    }
}
