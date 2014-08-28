<?php


/**
 * Catalog image helper
 *
 * @author      Paras Sood<paras89@live.com>
 */
class Parassood_Swatchswitcher_Helper_Image extends Mage_Catalog_Helper_Image
{

    /**
     * Initialize Helper to work with Image
     *
     * @param Mage_Catalog_Model_Product $product
     * @param string $attributeName
     * @param mixed $imageFile
     * @return Mage_Catalog_Helper_Image
     */
    public function init(Mage_Catalog_Model_Product $product, $attributeName, $imageFile=null)
    {
        //$product = $this->_getSimpleSwatchProduct($product);
        return parent::init($product,$attributeName,$imageFile);

    }

    /**
     * Mage_Catalog_Model_Product $product from which to retrieve swatch image.
     * @param $product
     * @return mixed
     */
    protected function _getSimpleSwatchProduct($product)
    {
        if(!$product->isConfigurable() || !($color = Mage::app()->getRequest()->getParam('color',false))){
            return $product;
        }
        $childProductsCollection = Mage::getModel('catalog/product_type_configurable')
            ->getUsedProductCollection($product)
            ->addAttributeToSelect('small_image')
            ->addAttributeToFilter('color', $color)
            ->load();
        if(count($childProductsCollection)){
            return $childProductsCollection->getFirstItem();
        }
        return $product;
    }

}
