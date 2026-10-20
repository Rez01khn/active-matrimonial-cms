@extends('frontend.layouts.app')

@section('portal_header')
    {{-- Header is placed inside the right app canvas to match full-height sidebar layout --}}
@endsection

@section('hide_header', true)
@section('hide_footer', true)
@section('hide_floating_cta', true)

@section('style')
    @yield('style')
@endsection

@section('content')
<div class="member-app-layout" id="memberAppLayout">
    <!-- Offcanvas Backdrop for Mobile / Tablet (< 1200px) -->
    <div class="member-sidebar-backdrop d-xl-none transition-opacity duration-300" id="memberSidebarBackdrop"></div>

    <!-- Left App-Style Dark Sidebar -->
    @include('frontend.member.sidebar')

    <!-- Right Main Content Canvas with Floral Corner Watermarks -->
    <div class="member-app-main-canvas" id="memberMainCanvas">
        <!-- Top-Right Floral Corner Watermark -->
        <svg class="canvas-floral-watermark-top" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M190 10 C 140 20, 110 60, 95 110 C 130 90, 165 60, 190 10 Z" fill="#8C6239" opacity="0.12"/>
            <path d="M195 40 C 160 55, 130 85, 120 130 C 150 110, 175 80, 195 40 Z" fill="#C5A059" opacity="0.10"/>
            <path d="M170 15 C 135 40, 115 80, 80 105 C 105 80, 140 50, 170 15 Z" fill="#9E1F30" opacity="0.08"/>
            <circle cx="115" cy="125" r="5" fill="#8C6239" opacity="0.15"/>
            <circle cx="95" cy="105" r="4" fill="#C5A059" opacity="0.12"/>
            <circle cx="140" cy="75" r="3" fill="#8C6239" opacity="0.15"/>
        </svg>

        <!-- Bottom-Right Floral Corner Watermark -->
        <svg class="canvas-floral-watermark-bottom" viewBox="0 0 240 240" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M230 230 C 170 215, 130 170, 110 110 C 150 140, 190 180, 230 230 Z" fill="#8C6239" opacity="0.10"/>
            <path d="M235 190 C 190 180, 150 140, 135 90 C 170 120, 205 155, 235 190 Z" fill="#C5A059" opacity="0.08"/>
            <path d="M200 235 C 160 200, 135 150, 90 120 C 120 150, 160 195, 200 235 Z" fill="#9E1F30" opacity="0.06"/>
            <circle cx="130" cy="95" r="5" fill="#8C6239" opacity="0.12"/>
            <circle cx="108" cy="115" r="4" fill="#C5A059" opacity="0.10"/>
        </svg>

        <!-- Top App Header -->
        @include('frontend.inc.member_header')

        <!-- Content Panel -->
        <main class="member-app-content-body">
            @yield('panel_content')
        </main>
    </div>
</div>

<script type="text/javascript">
(function() {
    function initMemberSidebar() {
        var layout = document.getElementById('memberAppLayout');
        var sidebar = document.getElementById('memberDarkSidebar');
        var backdrop = document.getElementById('memberSidebarBackdrop');

        if (!sidebar) return;

        // Restore desktop collapsed state
        if (window.innerWidth >= 1200 && localStorage.getItem('memberSidebarCollapsed') === '1' && layout) {
            layout.classList.add('sidebar-collapsed');
        }

        function closeDrawer() {
            if (sidebar) sidebar.classList.remove('active');
            if (backdrop) backdrop.classList.remove('active');
            document.body.classList.remove('member-sidebar-open');
        }

        function openDrawer() {
            if (sidebar) sidebar.classList.add('active');
            if (backdrop) backdrop.classList.add('active');
            document.body.classList.add('member-sidebar-open');
        }

        // Delegated clicks for robustness across all member pages
        document.addEventListener('click', function(e) {
            var mobileBtn = e.target.closest('#memberSidebarMobileToggle');
            if (mobileBtn) {
                e.preventDefault();
                openDrawer();
                return;
            }

            var collapseBtn = e.target.closest('#memberSidebarCollapseBtn');
            if (collapseBtn) {
                e.preventDefault();
                if (window.innerWidth < 1200) {
                    closeDrawer();
                } else if (layout) {
                    layout.classList.toggle('sidebar-collapsed');
                    var isCollapsed = layout.classList.contains('sidebar-collapsed');
                    localStorage.setItem('memberSidebarCollapsed', isCollapsed ? '1' : '0');
                }
                return;
            }

            var backdropEl = e.target.closest('#memberSidebarBackdrop');
            if (backdropEl) {
                e.preventDefault();
                closeDrawer();
                return;
            }

            var navLink = e.target.closest('.member-dark-nav-link');
            if (navLink && window.innerWidth < 1200) {
                closeDrawer();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                closeDrawer();
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMemberSidebar);
    } else {
        initMemberSidebar();
    }
})();
</script>
@endsection
