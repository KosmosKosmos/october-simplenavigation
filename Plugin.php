<?php namespace Zollerboy\Navigation;

use Backend;
use System\Classes\PluginBase;

/**
 * Navigation Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Navigation',
            'description' => 'No description provided yet...',
            'author'      => 'Zollerboy',
            'icon'        => 'icon-sitemap'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Zollerboy\Navigation\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'zollerboy.navigation.some_permission' => [
                'tab' => 'Navigation',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'navigation' => [
                'label'       => 'Navigation',
                'url'         => Backend::url('zollerboy/navigation/navigationitems'),
                'icon'        => 'icon-sitemap',
                'permissions' => ['zollerboy.navigation.*'],
                'order'       => 500,
            ],
        ];
    }
}
