<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
  /**
   * Determine whether the user can view any models.
   */
  public function viewAny(User $user): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('view-any Payment'));
  }

  /**
   * Determine whether the user can view the model.
   */
  public function view(User $user, Payment $payment): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('view Payment'));
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(User $user): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('create Payment'));
  }

  /**
   * Determine whether the user can update the model.
   */
  public function update(User $user, Payment $payment): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('update Payment'));
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, Payment $payment): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('delete Payment'));
  }

  /**
   * Determine whether the user can restore the model.
   */
  public function restore(User $user, Payment $payment): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('restore Payment'));
  }

  /**
   * Determine whether the user can permanently delete the model.
   */
  public function forceDelete(User $user, Payment $payment): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('force-delete Payment'));
  }
}
