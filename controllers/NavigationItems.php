<?php namespace Zollerboy\Navigation\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Navigation Items Back-end Controller
 */
class NavigationItems extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Zollerboy.Navigation', 'navigation', 'navigationitems');
    }
}
