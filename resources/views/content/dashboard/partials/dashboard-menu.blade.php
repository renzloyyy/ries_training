{{--
    Dashboard left menu.
    Keeping the navigation in a partial lets us add dashboard sections like
    Publications without making the main dashboard Blade file harder to read.
--}}
<div class="ra-tabs">
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
</div>
