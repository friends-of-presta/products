<?php
/**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

require_once __DIR__.'/vendor/autoload.php';

use PsDay\Hooks as ProductHooks;

/**
 * Module to present how Prestashop developers
 * can customize Product pages.
 */
class Products extends Module
{
    /**
     * @var array list of available Product hooks.
     */
    private $productHooks;

    public function __construct()
    {
        $this->name = 'products';
        $this->version = '1.0.0';
        $this->author = 'Mickaël Andrieu';
        parent::__construct();
        $this->displayName = 'Products';
        $this->description = 'Module to demonstrate how to customize Product pages';
        $this->ps_versions_compliancy = [
            'min' => '1.7.4.0',
            'max' => _PS_VERSION_,
        ];

        $this->productHooks = array_merge(ProductHooks::PRODUCT_LIST_HOOKS, ProductHooks::PRODUCT_FORM_HOOKS);
        dump($this->productHooks);
    }

    /**
     * Module installation.
     *
     * @return bool Success of the installation
     */
    public function install()
    {
        return parent::install()
            && $this->registerHook($this->productHooks)
            && $this->registerHook('actionAdminControllerSetMedia')
        ;
    }

    /**
     * Uninstall the module.
     *
     * @return bool Success of the uninstallation
     */
    public function uninstall()
    {
        return parent::uninstall()
            && $this->unregisterHook($this->productHooks)
            && $this->unregisterHook('actionAdminControllerSetMedia')
        ;
    }

    /**
     * @param $hookParams
     *
     * Helper to inject some styles in Back Office.
     */
    public function hookActionAdminControllerSetMedia(&$hookParams)
    {
        $this->context->controller->addCSS($this->_path.'public/css/hook_block.css');

        return $this->__call('actionAdminControllerSetMedia', $hookParams);
    }

    /**
     * Every Hook non registered will display a block to localize it in UI.
     * 
     * @param string $name the function name.
     * @param string $arguments the function arguments if any.
     */
    public function __call($name, $arguments = null) {
        if ($name == 'hookDisplayOverrideTemplate') {
            return;
        }

        $this->context->smarty->assign('name', $name);

        return $this->display(__FILE__ , 'views/templates/hook_block.tpl');
    }
}
