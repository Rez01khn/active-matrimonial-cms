@extends('frontend.layouts.app')

@section('portal_header')
    @include('frontend.inc.member_header')
@endsection

@section('content')
<section class="member-portal-section py-4">
    <div class="container">
        <!-- Offcanvas Backdrop for Mobile / Tablet (< 1200px) -->
        <div class="member-sidebar-backdrop d-xl-none" id="memberSidebarBackdrop"></div>

        <div class="d-flex align-items-start position-relative member-portal-layout">
            @include('frontend.member.sidebar')
            <div class="aiz-user-panel overflow-hidden member-portal-main-panel">
                @yield('panel_content')
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        // Toggle mobile sidebar drawer
        $('#memberSidebarToggle').on('click', function(e) {
            e.preventDefault();
            $('#memberSidebarWrap').addClass('active');
            $('#memberSidebarBackdrop').addClass('active');
            $('body').addClass('member-sidebar-open');
        });

        // Close mobile sidebar drawer
        function closeMemberSidebar() {
            $('#memberSidebarWrap').removeClass('active');
            $('#memberSidebarBackdrop').removeClass('active');
            $('body').removeClass('member-sidebar-open');
        }

        $('#memberSidebarCloseBtn, #memberSidebarBackdrop').on('click', function(e) {
            e.preventDefault();
            closeMemberSidebar();
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $('#memberSidebarWrap').hasClass('active')) {
                closeMemberSidebar();
            }
        });

        // Close when clicking a nav link on mobile
        if ($(window).width() < 1200) {
            $('.aiz-user-sidenav .aiz-side-nav-link').on('click', function() {
                if (!$(this).parent().hasClass('has-sub')) {
                    closeMemberSidebar();
                }
            });
        }
    });
</script>
@endsection
