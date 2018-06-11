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
use PsDay\AlternativeDescription;
use PsDay\ProductsCollection;

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
    }

    /**
     * Module installation.
     *
     * @return bool Success of the installation
     */
    public function install()
    {
        return parent::install()
            && AlternativeDescription::addToProductTable()
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
            && AlternativeDescription::removeToProductTable()
            && $this->unregisterHook($this->productHooks)
            && $this->unregisterHook('actionAdminControllerSetMedia')
        ;
    }

    /**
     * @param $hookParams
     *
     * Helper to inject some styles in Back Office.
     * @return string|void
     */
    public function hookActionAdminControllerSetMedia(&$hookParams)
    {
        $this->context->controller->addCSS($this->_path.'public/css/hook_block.css');

        return $this->__call('actionAdminControllerSetMedia', $hookParams);
    }

    /**
     * Display "alternative" in Product page.
     * @param type $hookParams
     * @return string
     */
    public function hookDisplayAdminProductsMainStepLeftColumnMiddle($hookParams)
    {
        $productId = $hookParams['id_product'];
        $formFactory = $this->get('form.factory');
        $twig = $this->get('twig');


        $form = AlternativeDescription::addToForm($productId, $formFactory);

        // You don't need to design your form, call only form_row(my_field) in
        // your template.
        return AlternativeDescription::setTemplateToProductPage($twig, $form);
    }

    /**
     * Add the field "alternative_description to Product table.
     */
    public function hookActionDispatcherBefore()
    {
        AlternativeDescription::addToProductDefinition();
    }

    /**
     * Manage the list of product fields available in the Product Catalog page.
     * @param type $hookParams
     */
    public function hookActionAdminProductsListingFieldsModifier(&$hookParams)
    {
        $hookParams['sql_select']['alternative_description'] = [
            'table' => 'p',
            'field' => 'alternative_description',
            'filtering' => "LIKE '%%%s%%'",
        ];
    }

    /**
     * Manage the list of products available in the Product Catalog page.
     * @param type $hookParams
     */
    public function hookActionAdminProductsListingResultsModifier(&$hookParams)
    {
        $hookParams['products'] = ProductsCollection::make($hookParams['products'])
            ->sortBy('alternative_description')
            ->all()
        ;
    }

    /**
     * Manage the information in a specific tab of Product Page.
     * @param type $hookParams
     * @return string
     */
    public function hookDisplayAdminProductsExtra(&$hookParams)
    {
        return $this->get('twig')->render('@PrestaShop/Products/module_panel.html.twig');
    }

    /**
     * Every Hook non registered will display a block to localize it in UI.
     *
     * @param string $name the function name.
     * @param string $arguments the function arguments if any.
     * @return string|void
     */
    public function __call($name, $arguments = null)
    {
        if ($name == 'hookDisplayOverrideTemplate') {
            return;
        }

        $this->context->smarty->assign('name', $name);

        return $this->display(__FILE__, 'views/templates/hook_block.tpl');
    }
}
