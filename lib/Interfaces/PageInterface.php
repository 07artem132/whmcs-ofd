<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 09.05.2019
 * Time: 14:22
 */

namespace WHMCS\Module\Addon\ofd\Interfaces;

use WHMCS\View\Menu\MenuFactory;

interface  PageInterface
{
    /**
     * @return string
     */
    public function getTemplateName(): string;

    /**
     * @return array
     */
    public function getVars(): array;

    /**
     * @return MenuFactory|null
     */
    public function getSubMenu(): ?MenuFactory;

    /**
     * @return array
     */
    public function getBreadcrumb(): array;
}