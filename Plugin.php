<?php namespace Zollerboy\Navigation;

use System\Classes\PluginBase;
use Backend;
use App;
use Event;

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
        if (!App::runningInBackend()) {
            return;
        }

        Event::listen('backend.page.beforeDisplay', function($controller, $action, $params) {
            if (get_class($controller) === 'Zollerboy\Navigation\Controllers\NavigationItems') {
                $controller->addCss('/plugins/zollerboy/navigation/assets/css/jquery-ui.min.css');
                $controller->addJs('/plugins/zollerboy/navigation/assets/javascript/jquery-ui.min.js');
            }
        });
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
