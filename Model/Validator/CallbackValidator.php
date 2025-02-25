<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Validator;

class CallbackValidator extends \Magento\Framework\Validator\AbstractValidator
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var string|null
     */
    protected ?string $errorMessage;

    /**
     * @param callable $callback
     * @param string|null $errorMessage
     */
    public function __construct(
        callable $callback,
        string $errorMessage = null
    ) {
        $this->callback = $callback;
        $this->errorMessage = $errorMessage;
    }

    /**
     * Returns true if and only if $value meets the validation requirements.
     *
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->_clearMessages();
        $callback = $this->callback;

        try {
            $isValid = $callback($value);
        } catch (\Exception $e) {
            $isValid = false;
            $this->_addMessages([$e->getMessage()]);
        }

        if (!$isValid && $this->errorMessage) {
            $this->_addMessages([$this->errorMessage]);
        }

        return $isValid;
    }
}
