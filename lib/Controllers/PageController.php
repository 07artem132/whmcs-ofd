<?php

namespace WHMCS\Module\Addon\ofd\Controllers;

use WHMCS\Module\Addon\ofd\Configs\ModuleConfig;
use WHMCS\Module\Addon\ofd\Configs\SmartyConfig;
use WHMCS\Module\Addon\ofd\Interfaces\PageInterface;
use WHMCS\View\Menu\MenuFactory;

class PageController
{
    /**
     * @var \Smarty
     */
    private $view;
    /**
     * @var string
     */
    private $action = '';
    /**
     * @var  MenuFactory
     */
    private $menu;
    /**
     * @var  MenuFactory
     */
    private $sub_menu;
    /**
     * @var  array
     */
    private $breadcrumb;

    /**
     * @var string
     */
    private $menu_template = '';
    /**
     * @var string
     */
    private $breadcrumb_template = '';
    /**
     * @var string
     */
    private $suffix = '';

    public function __construct($vars = array())
    {
        global $customadminpath, $CONFIG;

        $this->view = new \Smarty();
        $this->view->setTemplateDir(SmartyConfig::GetTemplateDir());
        $this->view->setCompileDir(SmartyConfig::GetCompileDir());
        $this->view->assign('_CONFIG', $CONFIG);

        if (array_key_exists('_lang', $vars))
            $this->view->assign('LANG', $vars['_lang']);

        $this->view->assign('csrfToken', generate_token('plain'));

        $this->view->assign('vars', $vars);
        $this->view->assign('customadminpath', $customadminpath);
        $this->view->assign('modulelink', ModuleConfig::getModuleLink());
    }

    public function setDefaultAction($action)
    {
        $this->action = $action;
    }

    public function setMenuTemplate($template)
    {
        $this->menu_template = $template;
    }
    public function setBreadcrumbTemplate($template)
    {
        $this->breadcrumb_template = $template;
    }

    public function setSuffixTemplate($suffix)
    {
        $this->suffix = $suffix;
    }

    public function setMenu($menu)
    {
        $this->menu = $menu;
    }

    private function getAction()
    {
        return isset($_REQUEST['action']) && !empty($_REQUEST['action']) ? $_REQUEST['action'] : $this->action;
    }

    public function run()
    {
        $this->view->assign('navbar', $this->menu);
        $this->displayMenu();

        $ClassName = ucfirst($this->suffix) . implode(array_map('ucfirst', array_map('strtolower', explode('_', $this->getAction())))) . 'Page';
        $ClassNameFull = sprintf('WHMCS\\Module\\Addon\\%s\\Pages\\%s',ModuleConfig::getModuleName(), $ClassName) ;
        try {
            /**
             * @var $class PageInterface
             */
            $class = new $ClassNameFull($this);

            if (empty($class)) {
                throw new \Exception('Page not Found');
            }

            foreach ($class->getVars() as $key => $var) {
                $this->view->assign($key, $var);
            }

            $this->sub_menu = $class->getSubMenu();

            if (!empty($this->sub_menu)) {
                $this->view->assign('navbar', $this->sub_menu);
                $this->displaySubMenu();
            }

            $this->breadcrumb = $class->getBreadcrumb();
            if (!empty($this->breadcrumb)) {
                $this->view->assign('breadcrumb', $this->breadcrumb);
                $this->displayBreadcrumb();
            }

            echo $this->view->fetch($class->getTemplateName());
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    private function displayMenu()
    {
        echo $this->view->fetch($this->menu_template);
    }

    private function displaySubMenu()
    {
        echo $this->view->fetch($this->menu_template);
    }
    private function displayBreadcrumb()
    {
        echo $this->view->fetch($this->breadcrumb_template);
    }


}