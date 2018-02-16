<?php namespace Zollerboy\Navigation\Models;

use Model;

use Cms\Classes\Page;
use Cms\Classes\Theme;

use Zollerboy\Navigation\Classes\Filesystem;

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

    public function getNewPageAttribute() {
        return 0;
    }


    public function afterFetch() {
        $this->link_change = $this->link;
    }

	public function beforeCreate() {

        $this->order = NavigationItem::where('parent_id', $this->parent_id)->orderBy('order', 'DESC')->pluck('order')->first() + 1;

    	if ($this->link_new !== null) {
            $this->link = $this->link_new;
            unset($this->link_new);

            $currentTheme = Theme::getEditTheme();
            $currentThemeDirectory = $currentTheme->getDirName();

            $pageFilename = "page" . str_replace("/", "-", $this->link) . ".htm";
            $contentFilename = "content" . str_replace("/", "-", $this->link) . ".htm";
            $pageFilePath = themes_path() . "/" . $currentThemeDirectory . "/pages/" . $pageFilename;
            $contentFilePath = themes_path() . "/" . $currentThemeDirectory . "/content/" . $contentFilename;

            $pageFileContents = [
                "title = \"" . $this->title . "\"\n",
                "url = \"" . $this->link . "\"\n",
                "layout = \"default\"\n",
                "is_hidden = 0\n",
                "\n",
                "[contenteditor]\n",
                "==\n",
                "{% component 'contenteditor' file=\"" . $contentFilename . "\" %}\n"
            ];

            Filesystem::createFile($pageFilename, $pageFileContents);
            Filesystem::createFile($contentFilePath);

        }

        unset($this->new_page);

	}

    public function beforeUpdate() {
        $currentTheme = Theme::getEditTheme();
        $currentThemeDirectory = $currentTheme->getDirName();

        $pageFilename = "page" . str_replace("/", "-", $this->link) . ".htm";
        $contentFilename = "content" . str_replace("/", "-", $this->link) . ".htm";
        $pageFilePath = themes_path() . "/" . $currentThemeDirectory . "/pages/" . $pageFilename;
        $contentFilePath = themes_path() . "/" . $currentThemeDirectory . "/content/" . $contentFilename;

        $newPageFilename = "page" . str_replace("/", "-", $this->link_change) . ".htm";
        $newContentFilename = "content" . str_replace("/", "-", $this->link_change) . ".htm";
        $newPageFilePath = themes_path() . "/" . $currentThemeDirectory . "/pages/" . $newPageFilename;
        $newContentFilePath = themes_path() . "/" . $currentThemeDirectory . "/content/" . $newContentFilename;

        $newPageReplaceRegex = [
            "/title = \"[\w \-]+\"/" => "title = \"" . $this->title . "\"",
            "/url = \"[a-z\.\-\/]+\"/" => "url = \"" . $this->link_change . "\"",
            "/\{% component 'contenteditor' file=\"[a-z\.\-]+\" %\}/" => "/\{% component 'contenteditor' file=\"" . $newContentFilename . "\" %\}/"
        ];

        Filesystem::updateFile($pageFilePath, $newPageFilePath, $newPageReplaceRegex);
        Filesystem::updateFile($contentFilePath, $newContentFilePath);

        $this->link = $this->link_change;
        unset($this->link_change);
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
