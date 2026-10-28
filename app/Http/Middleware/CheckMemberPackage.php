<?php

namespace App\Http\Middleware;

use App\Models\Package;
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

        // Auto-assign Free Package (Package ID 1) if user has no current package
        if ($user && $user->member && is_null($user->member->current_package_id)) {
            $free_pkg = Package::where('id', 1)->first() ?? Package::where('price', 0)->first();
            if ($free_pkg) {
                $user->member->current_package_id            = $free_pkg->id;
                $user->member->remaining_interest            = $free_pkg->express_interest ?? 0;
                $user->member->remaining_photo_gallery       = $free_pkg->photo_gallery ?? 0;
                $user->member->remaining_contact_view        = $free_pkg->contact ?? 0;
                $user->member->remaining_profile_viewer_view = $free_pkg->profile_viewers_view ?? 0;
                $user->member->remaining_profile_image_view  = $free_pkg->profile_image_view ?? 0;
                $user->member->remaining_gallery_image_view  = $free_pkg->gallery_image_view ?? 0;
                $user->member->auto_profile_match            = $free_pkg->auto_profile_match ?? 0;
                $user->member->package_validity              = date('Y-m-d', strtotime('+' . ($free_pkg->validity ?? 365) . ' days'));
                $user->member->save();

                $user->membership = 1;
                $user->has_purchased_free_package = 1;
                $user->save();
            }
        }

        return $next($request);
    }
}
