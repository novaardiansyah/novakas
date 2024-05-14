<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\PaymentType;
use App\Models\User;

class PaymentTypePolicy
{
  /**
   * Determine whether the user can view any models.
   */
  public function viewAny(User $user): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('view-any PaymentType'));
  }

  /**
   * Determine whether the user can view the model.
   */
  public function view(User $user, PaymentType $paymenttype): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('view PaymentType'));
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(User $user): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('create PaymentType'));
  }

  /**
   * Determine whether the user can update the model.
   */
  public function update(User $user, PaymentType $paymenttype): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('update PaymentType'));
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, PaymentType $paymenttype): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('delete PaymentType'));
  }

  /**
   * Determine whether the user can restore the model.
   */
  public function restore(User $user, PaymentType $paymenttype): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('restore PaymentType'));
  }

  /**
   * Determine whether the user can permanently delete the model.
   */
  public function forceDelete(User $user, PaymentType $paymenttype): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('force-delete PaymentType'));
  }
}
