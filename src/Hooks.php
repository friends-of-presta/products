<?php

namespace PsDay;

/**
 * Describe all specific hooks available in Product pages.
 */
class Hooks
{
    const PRODUCT_LIST_HOOKS = [
        'actionAdminProductsListingFieldsModifier',
        'actionAdminProductsListingResultsModifier',
        'displayBackOfficeTop',
        'displayDashboardToolbarTopMenu',
        'displayDashboardTop',
        'displayBackOfficeFooter',
        'displayAdminEndContent',
        'displayDashboardToolbarIcons',
    ];

    const PRODUCT_FORM_HOOKS = [
        'actionDispatcherBefore',
        'displayAdminProductsMainStepLeftColumnMiddle',
        'displayAdminProductsMainStepLeftColumnBottom',
        'displayAdminProductsMainStepRightColumnBottom',
        'displayAdminProductsQuantitiesStepBottom',
        'displayAdminProductsShippingStepBottom',
        'displayAdminProductsPriceStepBottom',
        'displayAdminProductsSeoStepBottom',
        'displayAdminProductsOptionsStepTop',
        'displayAdminProductsOptionsStepBottom',
        'displayAdminProductsExtra',
    ];
}
