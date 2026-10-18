<?php

namespace App\Models;

use App\Models\User;
use App\Models\Religion;
use App\Models\Caste;
use App\Models\SubCaste;
use App\Models\FamilyValue;
use App\Models\MemberLanguage;
use App\Models\MaritalStatus;
use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartnerExpectation extends Model
{
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class)->withTrashed();
    }

    public function caste()
    {
        return $this->belongsTo(Caste::class)->withTrashed();
    }

    public function sub_caste()
    {
        return $this->belongsTo(SubCaste::class)->withTrashed();
    }

    public function family_value()
    {
        return $this->belongsTo(FamilyValue::class)->withTrashed();
    }

    public function member_language()
    {
        return $this->belongsTo(MemberLanguage::class, 'language_id')->withTrashed();
    }

    public function marital_status()
    {
        return $this->belongsTo(MaritalStatus::class)->withTrashed();
    }

    public function residence_country()
    {
        return $this->belongsTo(Country::class, 'residence_country_id')->withTrashed();
    }

    public function preferred_country()
    {
        return $this->belongsTo(Country::class, 'preferred_country_id')->withTrashed();
    }

    public function preferred_state()
    {
        return $this->belongsTo(State::class, 'preferred_state_id')->withTrashed();
    }
}
