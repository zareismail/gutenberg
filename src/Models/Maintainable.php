<?php

namespace Zareismail\Gutenberg\Models;

trait Maintainable
{
    /**
     * Mark the model with the "maintenance" value.
     *
     * @return $this
     */
    public function asMaintenance()
    {
        return $this->markAs($this->getMaintenanceValue());
    }

    /**
     * Determine if the value of the model's "marked as" attribute is equal to the "maintenance" value.
     *
     * @param  string  $value
     * @return bool
     */
    public function isMaintenance()
    {
        return $this->markedAs($this->getMaintenanceValue());
    }

    /**
     * Query the model's `marked as` attribute with the "maintenance" value.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInMaintenance($query)
    {
        return $this->mark($this->getMaintenanceValue());
    }

    /**
     * Set the value of the "marked as" attribute as "maintenance" value.
     *
     * @return $this
     */
    public function setMaintenance()
    {
        return $this->setMarkedAs($this->getMaintenanceValue());
    }

    /**
     * Get the value of the "maintenance" mark.
     *
     * @return string
     */
    public function getMaintenanceValue()
    {
        return defined('static::MAINTENANCE_VALUE') ? static::MAINTENANCE_VALUE : 'maintenance';
    }
}
