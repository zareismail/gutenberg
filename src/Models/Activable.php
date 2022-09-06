<?php

namespace Zareismail\Gutenberg\Models;

use Zareismail\Markable\Markable;

trait Activable
{
    use Markable;

    /**
     * Mark the model with the "active" value.
     *
     * @return $this
     */
    public function asActive()
    {
        return $this->markAs($this->getActiveValue());
    }

    /**
     * Mark the model with the "inactive" value.
     *
     * @return $this
     */
    public function asInactive()
    {
        return $this->markAs($this->getActiveValue());
    }

    /**
     * Determine if the value of the model's "marked as" attribute is equal to the "active" value.
     *
     * @param  string  $value
     * @return bool
     */
    public function isActive()
    {
        return $this->markedAs($this->getActiveValue());
    }

    /**
     * Determine if the value of the model's "marked as" attribute is equal to the "inactive" value.
     *
     * @param  string  $value
     * @return bool
     */
    public function isInactive()
    {
        return $this->markedAs($this->getInactiveValue());
    }

    /**
     * Query the model's `marked as` attribute with the "active" value.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActivated($query)
    {
        return $this->mark($this->getActiveValue());
    }

    /**
     * Query the model's `marked as` attribute with the "inactive" value.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactivated($query)
    {
        return $this->mark($this->getInactiveValue());
    }

    /**
     * Set the value of the "marked as" attribute as "active" value.
     *
     * @return $this
     */
    public function setActive()
    {
        return $this->setMarkedAs($this->getActiveValue());
    }

    /**
     * Set the value of the "marked as" attribute as "inactive" value.
     *
     * @return $this
     */
    public function setInactive()
    {
        return $this->setMarkedAs($this->getInactiveValue());
    }

    /**
     * Get the value of the "active" mark.
     *
     * @return string
     */
    public function getActiveValue()
    {
        return defined('static::ACTIVE_VALUE') ? static::ACTIVE_VALUE : 'active';
    }

    /**
     * Get the value of the "inactive" mark.
     *
     * @return string
     */
    public function getInactiveValue()
    {
        return defined('static::INACTIVE_VALUE') ? static::INACTIVE_VALUE : 'inactive';
    }
}
