<?php

namespace Zareismail\Gutenberg\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\DAtabase\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;

abstract class Policy
{
    use HandlesAuthorization; 

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  \Illuminate\DAtabase\Eloquent\Model  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Authenticatable $user, Model $model)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Authenticatable $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  \Illuminate\DAtabase\Eloquent\Model  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Authenticatable $user, Model $model)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  \Illuminate\DAtabase\Eloquent\Model  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Authenticatable $user, Model $model)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  \Illuminate\DAtabase\Eloquent\Model  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Authenticatable $user, Model $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  \Illuminate\DAtabase\Eloquent\Model  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Authenticatable $user, Model $model)
    {
        //
    }
}
