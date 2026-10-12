<!-- Floating Sticky Consultation CTA -->
<a href="{{ route('consultations.index') }}" class="floating-consultation-btn" id="floatingConsultationBtn" title="{{ translate('Book Matchmaker Consultation') }}" aria-label="{{ translate('Book Matchmaker Consultation') }}">
    <div class="floating-consultation-icon-wrap">
        <span class="floating-consultation-pulse-ring"></span>
        <i class="las la-calendar-check"></i>
    </div>
    <div class="floating-consultation-content">
        <span class="floating-consultation-badge">
            <span class="badge-dot"></span>{{ translate('1-on-1 Assistance') }}
        </span>
        <span class="floating-consultation-title">{{ translate('Book Consultation') }}</span>
    </div>
    <div class="floating-consultation-arrow">
        <i class="las la-angle-right"></i>
    </div>
</a>
