<?php

namespace EDU\HelloWorld\Model;

class Product extends \Magento\Catalog\Model\Product
{
    public function getName()
    {
        $name = $this->_getData(self::NAME);
        return 'Awesome ' . $name;
    }
}
