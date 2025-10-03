<?php

namespace EDU\HelloWorld\Plugin;

use Magento\Review\Model\Review;

class CheckBefore
{

    public function afterValidate(Review $review, $result)
    {
        if (preg_match('/\-/', $review->getNickname())) {
            $result = is_array($result) ? $result : [];
            $result[] = __('Nickname cannot contain a dash.');
        }
        return $result;
    }

    public function beforeValidate(Review $review)
    {
        return null;
    }

}
