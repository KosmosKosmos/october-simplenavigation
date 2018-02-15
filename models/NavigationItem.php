<?php namespace Zollerboy\Navigation\Models;

use Model;
use File;
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
            $pageFilepath = themes_path() . "/" . $currentThemeDirectory . "/pages/" . $pageFilename;
            $contentFilepath = themes_path() . "/" . $currentThemeDirectory . "/content/" . $contentFilename;

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

            $pageFile = fopen($pageFilepath, "w");
            fwrite($pageFile, implode($pageFileContents));
            fclose($pageFile);

            $contentFile = fopen($contentFilepath, "w");
            fclose($contentFile);

        }

        unset($this->new_page);

	}

    public function beforeUpdate() {
        $currentTheme = Theme::getEditTheme();
        $currentThemeDirectory = $currentTheme->getDirName();

        $pageFilename = "page" . str_replace("/", "-", $this->link) . ".htm";
        $contentFilename = "content" . str_replace("/", "-", $this->link) . ".htm";
        $pageFilepath = themes_path() . "/" . $currentThemeDirectory . "/pages/" . $pageFilename;
        $contentFilepath = themes_path() . "/" . $currentThemeDirectory . "/content/" . $contentFilename;

        $newPageFilename = "page" . str_replace("/", "-", $this->link_change) . ".htm";
        $newContentFilename = "content" . str_replace("/", "-", $this->link_change) . ".htm";
        $newPageFilepath = themes_path() . "/" . $currentThemeDirectory . "/pages/" . $newPageFilename;
        $newContentFilepath = themes_path() . "/" . $currentThemeDirectory . "/content/" . $newContentFilename;

        rename($pageFilepath, $newPageFilepath);
        rename($contentFilepath, $newContentFilepath);

        $newPageFileContents = file($newPageFilepath);
        $newPageFileContents[0] = "title = \"" . $this->title . "\"\n";
        $newPageFileContents[1] = "url = \"" . $this->link_change . "\"\n";
        $newPageFileContents[count($newPageFileContents) - 1] = "{% component 'contenteditor' file=\"" . $newContentFilename . "\" %}\n";

        $pageFile = fopen($newPageFilepath, "w");
        fwrite($pageFile, implode($newPageFileContents));
        fclose($pageFile);


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
