<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\PaymentAccount;
use App\Models\User;

class PaymentAccountPolicy
{
  /**
   * Determine whether the user can view any models.
   */
  public function viewAny(User $user): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('view-any PaymentAccount'));
  }

  /**
   * Determine whether the user can view the model.
   */
  public function view(User $user, PaymentAccount $paymentaccount): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('view PaymentAccount') && $paymentaccount->user_id === auth()->id());
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(User $user): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('create PaymentAccount'));
  }

  /**
   * Determine whether the user can update the model.
   */
  public function update(User $user, PaymentAccount $paymentaccount): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('update PaymentAccount') && $paymentaccount->user_id === auth()->id());
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, PaymentAccount $paymentaccount): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('delete PaymentAccount') && $paymentaccount->user_id === auth()->id());
  }

  /**
   * Determine whether the user can restore the model.
   */
  public function restore(User $user, PaymentAccount $paymentaccount): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('restore PaymentAccount') && $paymentaccount->user_id === auth()->id());
  }

  /**
   * Determine whether the user can permanently delete the model.
   */
  public function forceDelete(User $user, PaymentAccount $paymentaccount): bool
  {
    return $user->checkPermissionTo('*') || ($user->checkPermissionTo('force-delete PaymentAccount') && $paymentaccount->user_id === auth()->id());
  }
}
