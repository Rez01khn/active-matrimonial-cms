<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Career;
use App\Models\Country;
use App\Models\Education;
use App\Models\ExpressInterest;
use App\Models\IgnoredUser;
use App\Models\Lifestyle;
use App\Models\MaritalStatus;
use App\Models\Member;
use App\Models\PhysicalAttribute;
use App\Models\ProfileMatch;
use App\Models\Religion;
use App\Models\Shortlist;
use App\Models\SpiritualBackground;
use App\Models\State;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileMatchController extends Controller
{
    /**
     * Compute and store profile matches for all or a specific user
     */
    public function match_profiles($user = ''): void
    {
        if (empty($user)) {
            $users = User::where('user_type', 'member')
                ->where('blocked', 0)
                ->where('approved', 1)
                ->where('deactivated', 0)
                ->get();
        } else {
            $users = User::where('id', $user)->get();
        }

        foreach ($users as $user) {
            $matches_attributes   = 0;
            $partner_expectations = $user->partner_expectations;

            if (!$partner_expectations) {
                continue;
            }

            $expected_residence_country  = $partner_expectations->residence_country_id ?? '';
            $expected_min_height         = $partner_expectations->height ?? '';
            $expected_max_weight         = $partner_expectations->weight ?? '';
            $expected_marital_status     = $partner_expectations->marital_status_id ?? '';
            $expected_religion           = $partner_expectations->religion_id ?? $partner_expectations->religion ?? '';
            $expected_caste              = $partner_expectations->caste_id ?? '';
            $expected_sub_caste_id       = $partner_expectations->sub_caste_id ?? '';
            $expected_language           = $partner_expectations->language_id ?? '';
            $expected_education          = $partner_expectations->education ?? '';
            $expected_profession         = $partner_expectations->profession ?? '';
            $expected_smoking_condition  = $partner_expectations->smoking_acceptable ?? '';
            $expected_drinking_condition = $partner_expectations->drinking_acceptable ?? '';
            $expected_diet_condition     = $partner_expectations->diet ?? '';
            $expected_state              = $partner_expectations->preferred_state_id ?? '';
            $expected_country            = $partner_expectations->preferred_country_id ?? '';
            $expected_family_value       = $partner_expectations->family_value_id ?? '';

            $partners = User::where('user_type', 'member')
                ->where('id', '!=', $user->id)
                ->where('blocked', 0)
                ->where('approved', 1)
                ->where('deactivated', 0)
                ->get();

            $user_ids = Member::where('gender', '!=', optional($user->member)->gender)->pluck('user_id')->toArray();
            if (count($user_ids) > 0) {
                $partners = $partners->whereIn('id', $user_ids);
            }

            // Total active criteria set
            $total_expected_attributes = 0;
            if ($expected_residence_country) $total_expected_attributes++;
            if ($expected_min_height) $total_expected_attributes++;
            if ($expected_max_weight) $total_expected_attributes++;
            if ($expected_marital_status) $total_expected_attributes++;
            if ($expected_language) $total_expected_attributes++;
            if ($expected_sub_caste_id || $expected_caste || $expected_religion) $total_expected_attributes++;
            if ($expected_education) $total_expected_attributes++;
            if ($expected_profession) $total_expected_attributes++;
            if ($expected_smoking_condition) $total_expected_attributes++;
            if ($expected_drinking_condition) $total_expected_attributes++;
            if ($expected_diet_condition) $total_expected_attributes++;
            if ($expected_state || $expected_country) $total_expected_attributes++;
            if ($expected_family_value) $total_expected_attributes++;

            foreach ($partners as $partner) {
                $matches_attributes = 0;

                if ($expected_residence_country) {
                    if (Address::where('user_id', $partner->id)->where('country_id', $expected_residence_country)->where('type', 'present')->exists()) {
                        $matches_attributes++;
                    }
                }

                if ($expected_min_height) {
                    if (PhysicalAttribute::where('user_id', $partner->id)->where('height', '>=', $expected_min_height)->exists()) {
                        $matches_attributes++;
                    }
                }

                if ($expected_max_weight) {
                    if (PhysicalAttribute::where('user_id', $partner->id)->where('weight', '<=', $expected_max_weight)->exists()) {
                        $matches_attributes++;
                    }
                }

                if ($expected_marital_status) {
                    if (Member::where('user_id', $partner->id)->where('marital_status_id', $expected_marital_status)->exists()) {
                        $matches_attributes++;
                    }
                }

                if ($expected_language) {
                    if (Member::where('user_id', $partner->id)->where('mothere_tongue', $expected_language)->exists()) {
                        $matches_attributes++;
                    }
                }

                // Religion & Caste
                if ($expected_religion) {
                    if (SpiritualBackground::where('user_id', $partner->id)->where('religion_id', $expected_religion)->exists()) {
                        $matches_attributes++;
                    }
                } elseif ($expected_caste) {
                    if (SpiritualBackground::where('user_id', $partner->id)->where('caste_id', $expected_caste)->exists()) {
                        $matches_attributes++;
                    }
                }

                if ($expected_education && $expected_education !== 'any') {
                    if (Education::where('user_id', $partner->id)->where('degree', 'like', '%' . $expected_education . '%')->exists()) {
                        $matches_attributes++;
                    }
                }

                if ($expected_profession && $expected_profession !== 'any') {
                    if (Career::where('user_id', $partner->id)->where('designation', 'like', '%' . $expected_profession . '%')->exists()) {
                        $matches_attributes++;
                    }
                }

                if ($expected_smoking_condition) {
                    if ($expected_smoking_condition === "dose_not_matter") {
                        $matches_attributes++;
                    } elseif (Lifestyle::where('user_id', $partner->id)->where('smoke', $expected_smoking_condition)->exists()) {
                        $matches_attributes++;
                    }
                }

                if ($expected_drinking_condition) {
                    if ($expected_drinking_condition === "dose_not_matter") {
                        $matches_attributes++;
                    } elseif (Lifestyle::where('user_id', $partner->id)->where('drink', $expected_drinking_condition)->exists()) {
                        $matches_attributes++;
                    }
                }

                if ($expected_diet_condition) {
                    if ($expected_diet_condition === "dose_not_matter") {
                        $matches_attributes++;
                    } elseif (Lifestyle::where('user_id', $partner->id)->where('diet', $expected_diet_condition)->exists()) {
                        $matches_attributes++;
                    }
                }

                if ($expected_state) {
                    if (Address::where('user_id', $partner->id)->where('state_id', $expected_state)->where('type', 'permanent')->exists()) {
                        $matches_attributes++;
                    }
                } elseif ($expected_country) {
                    if (Address::where('user_id', $partner->id)->where('country_id', $expected_country)->where('type', 'permanent')->exists()) {
                        $matches_attributes++;
                    }
                }

                if ($expected_family_value) {
                    if (SpiritualBackground::where('user_id', $partner->id)->where('family_value_id', $expected_family_value)->exists()) {
                        $matches_attributes++;
                    }
                }

                if ($total_expected_attributes > 0) {
                    $ratio = $matches_attributes / $total_expected_attributes;
                    $match_percentage = min(96, max(68, round(68 + ($ratio * 28))));
                } else {
                    $match_percentage = rand(74, 92);
                }

                $profile_match = ProfileMatch::firstOrNew(['user_id' => $user->id, 'match_id' => $partner->id]);
                $profile_match->match_percentage = $match_percentage;
                $profile_match->save();
            }
        }
    }

    /**
     * Display member's matched profiles with full search, multi-filters & sorting
     */
    public function myMatchedProfiles(Request $request): View|RedirectResponse
    {
        $user = Auth::user();

        if (optional($user->member)->auto_profile_match == 0) {
            flash(translate('Please update your membership package to view compatible profile matches.'))->error();
            return redirect()->route('packages');
        }

        // Auto-generate matches if none exist yet and partner expectations are present
        if (ProfileMatch::where('user_id', $user->id)->count() === 0 && $user->partner_expectations) {
            $this->match_profiles($user->id);
        }

        // Exclude ignored users
        $ignored_to = IgnoredUser::where('ignored_by', $user->id)->pluck('user_id')->toArray();
        $ignored_by_ids = IgnoredUser::where('user_id', $user->id)->pluck('ignored_by')->toArray();
        $excluded_ids = array_unique(array_merge($ignored_to, $ignored_by_ids));

        $query = ProfileMatch::with([
            'user.member.marital_status',
            'user.education',
            'user.career',
            'user.addresses.country',
            'user.addresses.state',
            'user.addresses.city',
            'user.spiritual_backgrounds.religion',
            'user.families'
        ])
        ->where('profile_matches.user_id', $user->id)
        ->whereHas('user', function ($uq) {
            $uq->where('blocked', 0)->where('approved', 1)->where('deactivated', 0);
        });

        if (count($excluded_ids) > 0) {
            $query->whereNotIn('profile_matches.match_id', $excluded_ids);
        }

        // 1. Keyword search (Name, ID, Degree, Designation)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->whereHas('user', function ($uq) use ($search) {
                $uq->where('first_name', 'like', "%{$search}%")
                   ->orWhere('last_name', 'like', "%{$search}%")
                   ->orWhere('code', 'like', "%{$search}%")
                   ->orWhereHas('career', function ($cq) use ($search) {
                       $cq->where('designation', 'like', "%{$search}%")
                          ->orWhere('company', 'like', "%{$search}%");
                   })
                   ->orWhereHas('education', function ($eq) use ($search) {
                       $eq->where('degree', 'like', "%{$search}%");
                   });
            });
        }

        // 2. Age Range Filter
        if ($request->filled('age_range') && $request->age_range !== 'all') {
            $range = explode('-', $request->age_range);
            if (count($range) === 2) {
                $minAge = (int)$range[0];
                $maxAge = (int)$range[1];
                $maxDate = Carbon::now()->subYears($minAge)->endOfDay();
                $minDate = Carbon::now()->subYears($maxAge + 1)->startOfDay();
                $query->whereHas('user.member', function ($mq) use ($minDate, $maxDate) {
                    $mq->whereBetween('birthday', [$minDate, $maxDate]);
                });
            } elseif ($request->age_range === '41+') {
                $maxDate = Carbon::now()->subYears(41)->endOfDay();
                $query->whereHas('user.member', function ($mq) use ($maxDate) {
                    $mq->where('birthday', '<=', $maxDate);
                });
            }
        }

        // 3. Religion Filter
        if ($request->filled('religion') && $request->religion !== 'all') {
            $religion_id = $request->religion;
            $query->whereHas('user.spiritual_backgrounds', function ($sq) use ($religion_id) {
                $sq->where('religion_id', $religion_id);
            });
        }

        // 4. Location Filter (Country or State)
        if ($request->filled('location') && $request->location !== 'all') {
            $location_id = $request->location;
            $query->whereHas('user.addresses', function ($aq) use ($location_id) {
                $aq->where('country_id', $location_id)
                   ->orWhere('state_id', $location_id)
                   ->orWhere('city_id', $location_id);
            });
        }

        // 5. Education Filter
        if ($request->filled('education') && $request->education !== 'all') {
            $edu_term = $request->education;
            $query->whereHas('user.education', function ($eq) use ($edu_term) {
                $eq->where('degree', 'like', "%{$edu_term}%");
            });
        }

        // 6. Marital Status Filter
        if ($request->filled('marital_status') && $request->marital_status !== 'all') {
            $ms_id = $request->marital_status;
            $query->whereHas('user.member', function ($mq) use ($ms_id) {
                $mq->where('marital_status_id', $ms_id);
            });
        }

        // 7. Sorting
        $sort = $request->get('sort', 'newest');
        if ($sort === 'match_high') {
            $query->orderBy('profile_matches.match_percentage', 'desc');
        } elseif ($sort === 'match_low') {
            $query->orderBy('profile_matches.match_percentage', 'asc');
        } elseif ($sort === 'age_asc') {
            $query->join('members', 'profile_matches.match_id', '=', 'members.user_id')
                  ->orderBy('members.birthday', 'desc')
                  ->select('profile_matches.*');
        } elseif ($sort === 'age_desc') {
            $query->join('members', 'profile_matches.match_id', '=', 'members.user_id')
                  ->orderBy('members.birthday', 'asc')
                  ->select('profile_matches.*');
        } else {
            // Default: highest match or newest
            $query->orderBy('profile_matches.match_percentage', 'desc')->latest('profile_matches.created_at');
        }

        $matchedProfiles = $query->paginate(16)->withQueryString();

        // Refresh cooldown check
        $canRefresh = true;
        $nextRefresh = null;
        if ($user->match_refresh_updated_at) {
            $nextRefresh = Carbon::parse($user->match_refresh_updated_at)->addMinutes(30);
            if ($nextRefresh->isFuture()) {
                $canRefresh = false;
            }
        }

        // Data for dropdown options
        $religions = Religion::all();
        $marital_statuses = MaritalStatus::all();
        $countries = Country::where('status', 1)->get();
        $states = State::all();

        // Preload user's shortlisted and expressed interest candidate IDs to prevent N+1 queries
        $shortlisted_ids = Shortlist::where('shortlisted_by', $user->id)->pluck('user_id')->toArray();
        $expressed_interest_ids = ExpressInterest::where('interested_by', $user->id)->pluck('user_id')->toArray();

        return view('frontend.member.matched_profiles', compact(
            'matchedProfiles',
            'religions',
            'marital_statuses',
            'countries',
            'states',
            'shortlisted_ids',
            'expressed_interest_ids',
            'canRefresh',
            'nextRefresh',
            'sort'
        ));
    }

    /**
     * Refresh matches on demand
     */
    public function matchedRefresh(): RedirectResponse
    {
        $user = Auth::user();

        if ($user->partner_expectations) {
            if (optional($user->member)->auto_profile_match != 1) {
                flash(translate('Auto match is disabled for your account.'))->error();
                return back();
            }

            $this->match_profiles($user->id);

            $user->update([
                'match_refresh_updated_at' => now(),
            ]);

            flash(translate('Profile matches refreshed successfully with latest preferences.'))->success();
            return back();
        }

        flash(translate('Please complete your partner expectations first to generate matches.'))->error();
        return back();
    }
}
