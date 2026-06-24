{{--
    Dashboard left menu.
    Keeping the navigation in a partial lets us add dashboard sections like
    Publications without making the main dashboard Blade file harder to read.
--}}
<div class="ra-tabs">
    <div class="ra-tabs-brand">
        <div class="ra-tabs-mark" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
                <path
                    d="M4.5 6.5v11.2c2.2-.9 4.6-.7 7.5.8 2.9-1.5 5.3-1.7 7.5-.8V6.5c-2.2-.9-4.6-.7-7.5.8-2.9-1.5-5.3-1.7-7.5-.8Z"
                    stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" />
                <path d="M12 7.3v11.2M15.6 10.1l-2.1 2.1M16.3 13.4h-2.8" stroke="currentColor" stroke-width="1.6"
                    stroke-linecap="round" />
            </svg>
        </div>
        {{-- The sidebar menu is vertical because the container stacks each
             block downward, so the title can stay in its normal two-line form. --}}
        <div class="ra-tabs-title">Research<br>Program</div>
        <div class="ra-tabs-subtitle">Analytics dashboard</div>
    </div>

    <button type="button" class="ra-tab is-active" data-tab="overview">
        <svg class="ra-tab-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M3.5 10.8 12 4l8.5 6.8v8.7a1 1 0 0 1-1 1h-5v-6h-5v6h-5a1 1 0 0 1-1-1v-8.7Z" stroke-width="1.7"
                stroke-linejoin="round" />
        </svg>
        Overview
    </button>

    <button type="button" class="ra-tab" data-tab="proposals">
        <svg class="ra-tab-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M7 3.5h7l3 3v14H7v-17Z" stroke-width="1.7" stroke-linejoin="round" />
            <path d="M14 3.5v3h3M9.5 11h5M9.5 14.5h5" stroke-width="1.7" stroke-linecap="round" />
        </svg>
        Proposals
    </button>

    <button type="button" class="ra-tab" data-tab="publications">
        <svg class="ra-tab-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M5 4.5h10.5L19 8v11.5H5v-15Z" stroke-width="1.7" stroke-linejoin="round" />
            <path d="M15.5 4.5V8H19M8.5 12h7M8.5 15.5h5" stroke-width="1.7" stroke-linecap="round" />
        </svg>
        Publications
    </button>

    <button type="button" class="ra-tab" data-tab="fundings">
        <svg class="ra-tab-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 3.5v17M7.5 7.5h6.25a3.25 3.25 0 0 1 0 6.5H9.5" stroke-width="1.7"
                stroke-linecap="round" stroke-linejoin="round" />
            <path d="M6 20.5h12" stroke-width="1.7" stroke-linecap="round" />
        </svg>
        Fundings
    </button>

    <div class="ra-tabs-quote">Advancing knowledge through impactful research and collaboration.</div>
</div>
