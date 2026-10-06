<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMemberPackage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($request->routeIs('member_profile') || $request->is('member-profile/*')) {
            $target_id = $request->route('id');
            if ($user && $user->user_type == 'member' && ($target_id != $user->id) && !is_paid_member($user->id)) {
                flash(translate('Please upgrade to a premium package to view complete biodata and profile details.'))->warning();
                return redirect()->route('packages');
            }
        }

        if ($user && $user->member && is_null($user->member->current_package_id)) {

            if (!$request->routeIs(['home', 'packages', 'package_payment_methods', 'free_package_purchase', 'package_payment.invoice', 'package_purchase_history', 'package.payment'])) {
                flash(translate('Please purchase a package first.'))->warning();
                return redirect()->route('packages');
            }
        }

        return $next($request);
    }
}
