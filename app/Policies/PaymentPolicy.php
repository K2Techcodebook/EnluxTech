<?php

namespace App\Policies;

use App\Payment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization;

    public function use(?User $user, ?Payment $payment, $amount)
    {
      if (!$payment) {
        abort(400, "Payment With Reference Does Not Exist");
      }

      if ($payment->status != 'success') {
        abort(400, "Payment With Reference Was Not Successful");
      }

      if ($payment->transaction) {
        abort(400, "Payment Already Used For Another Service");
      }

      if ($payment->amount < $amount) {
        abort(400, "Amount Paid Cannot Be Less Than Purchase Amount");
      }

      return true;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(?User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Payment  $payment
     * @return mixed
     */
    public function view(?User $user, Payment $payment)
    {

    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(?User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Payment  $payment
     * @return mixed
     */
    public function update(?User $user, Payment $payment)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Payment  $payment
     * @return mixed
     */
    public function delete(?User $user, Payment $payment)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Payment  $payment
     * @return mixed
     */
    public function restore(?User $user, Payment $payment)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Payment  $payment
     * @return mixed
     */
    public function forceDelete(?User $user, Payment $payment)
    {
        //
    }
}
