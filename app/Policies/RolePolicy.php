<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Role;
use App\Models\User;

class RolePolicy
{
  /**
   * Determine whether the user can view any models.
   */
  public function viewAny(User $user): bool
  {
    return false;
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('view-any Role'));
  }

  /**
   * Determine whether the user can view the model.
   */
  public function view(User $user, Role $role): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('view Role'));
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(User $user): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('create Role'));
  }

  /**
   * Determine whether the user can update the model.
   */
  public function update(User $user, Role $role): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('update Role'));
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, Role $role): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('delete Role'));
  }

  /**
   * Determine whether the user can restore the model.
   */
  public function restore(User $user, Role $role): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('restore Role'));
  }

  /**
   * Determine whether the user can permanently delete the model.
   */
  public function forceDelete(User $user, Role $role): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('force-delete Role'));
  }
}
