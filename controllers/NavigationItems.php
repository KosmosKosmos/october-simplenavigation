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

        $this->addCss('/plugins/zollerboy/navigation/assets/css/jquery-ui.min.css');
        $this->addJs('/plugins/zollerboy/navigation/assets/javascript/jquery-ui.min.js');
    }
}
