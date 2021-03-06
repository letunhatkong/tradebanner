<?php
/**
 * Copyright © 2015 J2T Design. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace J2t\Rewardpoints\Model;

class Session extends \Magento\Framework\Session\Generic
{
    /**
     * Set error message
     *
     * @param string $message
     * @return $this
     */
    public function addError($message)
    {
        $this->setErrorMessage($message);
        return $this;
    }

    /**
     * Set success message
     *
     * @param string $message
     * @return $this
     */
    public function addSuccess($message)
    {
        $this->setSuccessMessage($message);
        return $this;
    }

    /**
     * Get error message
     *
     * @return string $message
     */
    public function getError()
    {
        $message = $this->getErrorMessage();
        $this->unsErrorMessage();
        return $message;
    }

    /**
     * Get success message
     *
     * @return string $message
     */
    public function getSuccess()
    {
        $message = $this->getSuccessMessage();
        $this->unsSuccessMessage();
        return $message;
    }
    
    public function unsetAll()
    {
        parent::clearStorage();
        return $this;
    }
}
