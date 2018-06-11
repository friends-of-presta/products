<?php

namespace PsDay;

use Db;
use Product;
use Twig\Environment;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * A a new field "alternative_description" to Product Page
 */
class AlternativeDescription
{
    /**
     * Update Product class definition.
     */
    public static function addToProductDefinition()
    {
        $productFields = Product::$definition['fields'];

        if (!array_key_exists('alternative_description', $productFields)) {
            Product::$definition['fields']['alternative_description'] = [
                'type' => Product::TYPE_HTML,
                'lang' => false,
                'validate' => 'isCleanHtml'
            ];
        }
    }

    /**
     * Add the field to product table.
     *
     * @return bool success of the update
     */
    public static function addToProductTable()
    {
        return Db::getInstance()->execute("ALTER TABLE " . _DB_PREFIX_ . "product " . "ADD alternative_description TEXT NULL");
    }

    /**
     * Remove the field to product table.
     *
     * @return bool success of the update
     */
    public static function removeToProductTable()
    {
        return Db::getInstance()->execute("ALTER TABLE " . _DB_PREFIX_ . "product " . "DROP alternative_description");
    }

    /**
     * Add the extra field to the Product form.
     * @param $productId
     * @param FormFactoryInterface $formFactory
     * @return \Symfony\Component\Form\FormInterface
     */
    public static function addToForm($productId, FormFactoryInterface $formFactory)
    {
        $product = new Product($productId);

        return $formFactory
            ->createNamedBuilder('alternative_description', TextType::class, $product->alternative_description)
            ->getForm()
        ;
    }

    /**
     * Optional but useful if you need to setup your own specific template.
     *
     * @param Environment $twig The Twig renderer.
     * @param FormInterface $form The Product form.
     *
     * @return string
     */
    public static function setTemplateToProductPage(Environment $twig, FormInterface $form)
    {
        $template = '@PrestaShop/Products/alternative_description.html.twig';

        return $twig->render($template, [
            'alternative_description' => $form->createView()
        ]);
    }
}