<?php namespace Zollerboy\Navigation\Models;

use Model;
use Cms\Classes\Theme;
use Cms\Classes\Page;

/**
 * NavigationItem Model
 */
class NavigationItem extends Model {
    /**
     * @var string The database table used by the model.
     */
    public $table = 'zollerboy_navigation_items';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        "sub_items" => ['Zollerboy\Navigation\Models\NavigationItem', "key" => "parent_id"]
    ];
    public $belongsTo = [
        "parent" => "Zollerboy\Navigation\Models\NavigationItem"
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public $appends = ["newpage"];

    public function getNewpageAttribute() {
        return 0;
    }


	public function beforeSave() {

    	if ($this->link_new !== null) {
            $this->link = $this->link_new;
            unset($this->link_new);
        }

        unset($this->newpage);

	}

    public function getLinks($fieldName, $value, $formData) {

        $links = array();

        $currentTheme = Theme::getEditTheme();
        $pages = Page::listInTheme($currentTheme, true);

        foreach ($pages as $page) {
            $links[$page->url] = $page->url . " (" . $page->title . ")";
        }

		return $links;
	}
}
