<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Page;

class Context
{
    /**
     * @var array
     */
    protected array $data = [];

    /**
     * Get value.
     *
     * @param string $name
     * @return mixed
     */
    public function getValue(string $name): mixed
    {
        return $this->data[$name] ?? null;
    }

    /**
     * Set value.
     *
     * @param string $name
     * @param mixed $value
     * @return self
     */
    public function setValue(string $name, mixed $value): self
    {
        $this->data[$name] = $value;
        return $this;
    }

    /**
     * Unset value.
     *
     * @param string $name
     * @return self
     */
    public function unsValue(string $name): self
    {
        unset($this->data[$name]);
        return $this;
    }
}
