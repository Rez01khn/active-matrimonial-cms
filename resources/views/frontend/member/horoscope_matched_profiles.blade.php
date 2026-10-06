@extends('frontend.layouts.member_panel')
@section('panel_content')
    <div class="card">
        @php
          $canRefresh = true;
          if(Auth::user()->refresh_updated_at){
            $nextRefresh = \Carbon\Carbon::parse(Auth::user()->refresh_updated_at)->addMinutes(30);
            if($nextRefresh->isFuture()){
                $canRefresh = false;
            }
          }
        @endphp
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0 h6">{{ translate('Horoscope Matched Profiles') }}</h5>
          @if($canRefresh)
              <a href="{{ route('horoscope.match.refresh') }}"
                class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                title="{{ translate('Refresh') }}">
                  <i class="las la-sync"></i>
              </a>
          @else
              <button class="btn btn-soft-secondary btn-icon btn-circle btn-sm"
                      disabled
                      title="{{ translate('You can refresh after 30 minutes') }}">
                  <i class="las la-sync"></i>
              </button>
          @endif
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>{{translate('Image')}}</th>
                      <th>{{translate('Name')}}</th>
                      <th data-breakpoints="lg">{{translate('Age')}}</th>
                      @if(get_setting('member_spiritual_and_social_background_section') == 'on')
                        <th data-breakpoints="lg">{{translate('Religion')}}</th>
                      @endif
                      @if(get_setting('member_present_address_section') == 'on')
                        <th data-breakpoints="lg">{{translate('Location')}}</th>
                      @endif
                      @if(get_setting('member_language_section') == 'on')
                        <th data-breakpoints="lg">{{translate('Mother  Tongue')}}</th>
                      @endif
                      <th class="text-right" data-breakpoints="lg">{{translate('Options')}}</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($matchedProfiles as $key => $matchedProfile)
                      @if($matchedProfile->user != null)
                      <tr>
                          <td>{{ ($key+1) + ($matchedProfiles->currentPage() - 1)*$matchedProfiles->perPage() }}</td>
                          <td>
                            <a href="{{ route('member_profile', $matchedProfile->user->id) }}" class="text-reset c-pointer">
                                @if(uploaded_asset($matchedProfile->user->photo) != null)
                                    <img class="img-md w-45px h-45px" src="{{ uploaded_asset($matchedProfile->user->photo) }}"  alt="{{translate('photo')}}">
                                @else
                                    <img class="img-md w-45px h-45px" src="{{ static_asset('assets/img/avatar-place.png') }}" alt="{{translate('photo')}}">
                                @endif
                            </a>
                          </td>
                          <td>
                              <a href="{{ route('member_profile', $matchedProfile->user->id) }}" class="text-reset c-pointer">
                                {{ $matchedProfile->user->first_name.' '.$matchedProfile->user->last_name }}
                            </a>
                          </td>
                          <td>{{ \Carbon\Carbon::parse($matchedProfile->user->member->birthday)->age }}</td>
                          @if(get_setting('member_spiritual_and_social_background_section') == 'on')
                          <td>
                            @if(!empty($matchedProfile->user->spiritual_backgrounds->religion_id))
                                {{ $matchedProfile->user->spiritual_backgrounds->religion->name }}
                            @endif
                          </td>
                          @endif
                          @if(get_setting('member_present_address_section') == 'on')
                          <td>
                            @php
                                $present_address = \App\Models\Address::where('type','present')->where('user_id', $matchedProfile->user->id)->first();
                            @endphp
                            @if(!empty($present_address->country_id))
                                {{ $present_address->country->name }}
                            @endif
                          </td>
                          @endif
                          @if(get_setting('member_language_section') == 'on')
                          <td>
                            @if($matchedProfile->user->member->mothere_tongue != null)
                                {{ \App\Models\MemberLanguage::where('id',$matchedProfile->user->member->mothere_tongue)->first()->name }}
                            @endif
                          </td>
                          @endif
                          <td class="text-right">
                              @php
                                $expressed_interest = \App\Models\ExpressInterest::where('user_id', $matchedProfile->user->id)->where('interested_by',Auth::user()->id)->first();
                                $received_expressed_interest = \App\Models\ExpressInterest::where('user_id', Auth::user()->id)
                                                                ->where('interested_by', $matchedProfile->user->id)
                                                                ->first();
                              @endphp
                              @if(empty($expressed_interest) && empty($received_expressed_interest))
                                <a href="javascript:void(0);" onclick="express_interest({{ $matchedProfile->user->id }})" id="interest_a_id_{{ $matchedProfile->user->id }}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('Express Interest') }}">
                                    <i class="las la-heart"></i>
                                </a>
                              @elseif($received_expressed_interest)
                                @if($received_expressed_interest->status == 0)
                                  <a href="{{ route('interest_requests') }}" class="btn btn-soft-success btn-icon btn-circle btn-sm" title="{{ translate('Response to Interest') }}">
                                    <i class="las la-heart"></i>
                                  </a>
                                @else
                                  <a href="javascript:void(0);" class="btn btn-soft-success btn-icon btn-circle btn-sm" title="{{ translate('You Accepted Interest of This Member') }}">
                                    <i class="las la-heart"></i>
                                  </a>
                                @endif
                              @else
                                <a href="javascript:void(0);" class="btn btn-soft-success btn-icon btn-circle btn-sm" title="{{ translate('Interest Expressed') }}">
                                    <i class="las la-heart"></i>
                                </a>
                              @endif
                          </td>
                      </tr>
                    @endif

                  @endforeach
              </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $matchedProfiles->links() }}
            </div>
        </div>
    </div>
@endsection

@section('modal')
  @include('modals.confirm_modal')
  @include('modals.package_update_alert_modal')
@endsection

@section('script')
<script type="text/javascript">

  var is_paid_user = {{ is_paid_member(Auth::id()) ? 'true' : 'false' }};

  function express_interest(id)
  {
    if(!is_paid_user){
        $('#package_alert_title').text("{{ translate('Upgrade to Express Interest') }}");
        $('#package_alert_text').text("{{ translate('Please upgrade to a premium package to send matchmaking proposals.') }}");
        $('.package_update_alert_modal').modal('show');
        return;
    }

    $("#interest_a_id_"+id).removeAttr("onclick");
    $.post('{{ route('express-interest.store') }}',
      {
        _token: '{{ csrf_token() }}',
        id: id
      },
      function (data) {
        if (data == 1 || (data && data.result)) {
          $("#interest_a_id_"+id).attr("class","btn btn-soft-success btn-icon btn-circle btn-sm");
          $("#interest_a_id_"+id).attr("title","{{ translate('Interest Sent') }}");
          AIZ.plugins.notify('success', '{{translate('Interest expressed successfully!')}}');
        }
        else if (data && data.status === 'upgrade_required') {
          $('#package_alert_title').text("{{ translate('Upgrade to Express Interest') }}");
          $('#package_alert_text').text(data.message || "{{ translate('Please upgrade to a premium package to send matchmaking proposals.') }}");
          $('.package_update_alert_modal').modal('show');
          $("#interest_a_id_"+id).attr("onclick","express_interest("+id+")");
        }
        else {
          $("#interest_a_id_"+id).attr("onclick","express_interest("+id+")");
          AIZ.plugins.notify('danger', (data && data.message) ? data.message : '{{translate('Something went wrong')}}');
        }
      }
    ).fail(function() {
      $("#interest_a_id_"+id).attr("onclick","express_interest("+id+")");
      AIZ.plugins.notify('danger', '{{translate('Something went wrong')}}');
    });
  }

  function package_update_alert(){
    $('.package_update_alert_modal').modal('show');
  }

</script>
@endsection
