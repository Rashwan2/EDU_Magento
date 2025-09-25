<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\HelloWorld\Block;


use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Provide information to frontend storage manager
 *
 * @api
 * @since 102.0.0
 */
class Display extends Template
{
    public function __construct(
        Context $context,

        array $data = []
    ) {
        parent::__construct($context, $data);

    }
}
