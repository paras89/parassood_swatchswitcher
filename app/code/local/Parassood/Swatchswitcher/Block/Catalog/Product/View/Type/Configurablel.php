<?php

class Parassood_Swatchswitcher_Block_Catalog_Product_View_Type_Configurablel
    extends Amasty_Conf_Block_Catalog_Product_View_Type_Configurablel
{


    public function getJsonConfig()
    {
        $jsonConfig = parent::getJsonConfig();
        $config = Zend_Json::decode($jsonConfig);
        $productImagesAttributes = $this->getImagesFromProductsAttributes();
        $swatchMainArray = array();

        foreach ($config['attributes'] as $attributeId => $attribute) {
            $attr = Mage::getModel('amconf/attribute')->load($attributeId, 'attribute_id');


            if ($attr->getUseImage()) {

                foreach ($attribute['options'] as $i => $option) {
                    $this->_optionProducts[$attributeId][$option['id']] = $option['products'];
                    if (in_array($attributeId, $productImagesAttributes)) {

                        continue; // WE continue since we won't be using this scenario since this scenario fetches image from the product and not the configurable attribute.
                        // Do nothing, continue with other products.
                        foreach ($option['products'] as $product_id) {

                            $product = Mage::getModel('catalog/product')->load($product_id);
                            $config['attributes'][$attributeId]['options'][$i]['image'] =
                                (string)Mage::helper('catalog/image')->init($product, 'image')->resize($smWidth, $smHeight);
                            if (in_array($attr->getCatUseTooltip(), array("2", "3")))
                                $config['attributes'][$attributeId]['options'][$i]['bigimage'] =
                                    (string)Mage::helper('catalog/image')->init($product, 'image')->resize($bigWidth, $bigHeight);
                            break;
                        }
                    } else {
                        $product = Mage::getModel('catalog/product')->load($option['products'][0]);
                        if (array_key_exists($product->getPrimaryColor(), $swatchMainArray)) {
                            // We have already sent a swatch image for this primary color type. Unset other repeats in config data.
                            unset($config['attributes'][$attributeId]['options'][$i]['image']);
                            if (in_array($attr->getCatUseTooltip(), array("2", "3")))
                                unset($config['attributes'][$attributeId]['options'][$i]['bigimage']);

                        } else {
                            // Register entry of the swatch for this primary color type.
                            $swatchMainArray[$product->getPrimaryColor()] = 1;
                        }

                    }
                }
            }

        }
        $this->_jsonConfig = $config;
        return Zend_Json::encode($config);
    }

}