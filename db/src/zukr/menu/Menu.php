<?php

namespace zukr\menu;

/**
 * Class Menu
 *
 * @package zukr\menu
 */
class Menu
{
    /**
     * @var array
     */
    protected $_menu;

    public function __construct(array $_menu)
    {
        $this->_menu = $_menu;
    }


    public function getMenu()
    {
        $headMenu = $this->renderHeadMenu();
        $subMenu  = $this->renderSubMenu();
        $__menu   = <<< __HTML__
 <div id="tabs-container">
        <ul class="tabs">
            {$headMenu}
        </ul>
    </div>
 <div id="nav-container">
 {$subMenu}
</div>
<div style="clear:both"></div>
__HTML__;
        return $__menu;

    }


    /**
     * @return string
     */
    private function renderHeadMenu()
    {
        $str = '';
        foreach ($this->_menu as $item) {
            $str .= $this->item($item);
        }
        return $str;
    }

    private function renderSubMenu()
    {
        $subMenu = '';
        foreach ($this->_menu as $id => $_subMenu) {
            if (isset($_subMenu['items']) && is_array($_subMenu['items']) && !empty($_subMenu['items'])) {
                $_subMenu_ = '';
                foreach ($_subMenu['items'] as $item) {
                    if (isset($item['items']) && is_array($item['items']) && !empty($item['items'])) {
                        $subSubMenu = $this->renderSubSubMenu($item['items']);
                        $content = "<ul class='sub'>\n{$subSubMenu}</ul>\n";

                        $_subMenu_ .= $this->item($item, $content);
                    } else {
                        $_subMenu_ .= $this->item($item);
                    }
                }
            }
            $style   = isset($_subMenu['active']) ? '' : "style='display:none'";
            $subMenu .= "<ul class='nav' id='" . ($id + 1) . "' {$style}>{$_subMenu_}</ul>" . PHP_EOL;
        }
        return $subMenu;
    }


    private function renderSubSubMenu(array $items)
    {
        $itemList = '';
        foreach ($items as $item) {
            $itemList .= $this->item($item);
        }
        return $itemList;
    }

    /**
     * @param array $item
     * @return string
     */
    private function item(array $item, $content = '')
    {
        $href   = isset($item['href']) ? $item['href'] : '#';
        $title  = isset($item['title']) ? " title='{$item['title']}' " : '';
        $class  = isset($item['class']) ? " class='{$item['class']}' " : '';
        $active = isset($item['active']) ? "class='active' " : '';
        return "<li $active><a  href='{$href}' $title  $class >{$item['value']}</a>$content</li>" . PHP_EOL;
    }


}