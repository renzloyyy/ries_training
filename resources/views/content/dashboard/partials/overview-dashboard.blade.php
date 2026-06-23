{{--
    Overview dashboard panel.
    This page keeps only lead indicators from each fact table so users see
    the executive summary before opening the detailed dashboards.
--}}
<div class="row g-3 mb-3">
    <div class="col-12">
        <div class="ra-card">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Proposal lead indicators</h2>
                    <div class="ra-card-sub">summary from fact_research_proposal</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-sm-6 col-lg-4">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Total proposals</div>
            <div class="ra-kpi-value" id="kpiTotal"><span class="ra-skel"></span></div>
            <div class="ra-kpi-foot">across all campuses</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Approval rate</div>
            <div class="ra-kpi-value" id="kpiApprovalRate"><span class="ra-skel"></span></div>

        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Campuses reporting</div>
            <div class="ra-kpi-value" id="kpiCampuses"><span class="ra-skel"></span></div>
            <div class="ra-kpi-foot">with at least one proposal</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12">
        <div class="ra-card">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Publication lead indicators</h2>
                    <div class="ra-card-sub">summary from ri_submission (soulsuedu_ries)</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-sm-6 col-lg-3">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Total outputs</div>
            <div class="ra-kpi-value" id="overviewTotalOutputs"><span class="ra-skel"></span></div>
            <div class="ra-kpi-foot">all research outputs</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Completed outputs</div>
            <div class="ra-kpi-value" id="overviewCompletedOutputs"><span class="ra-skel"></span></div>
            <div class="ra-kpi-foot">based on complete count</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Output completion</div>
            <div class="ra-kpi-value" id="overviewOutputCompletionRate"><span class="ra-skel"></span></div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Active output campuses</div>
            <div class="ra-kpi-value" id="overviewOutputCampuses"><span class="ra-skel"></span></div>
            <div class="ra-kpi-foot">with at least one output</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12">
        <div class="ra-card">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Funding lead indicators</h2>
                    <div class="ra-card-sub">summary from the funding fact table</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-sm-6 col-lg-4">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Total funded projects</div>
            <div class="ra-kpi-value" id="overviewFundedProjects">—</div>
            <div class="ra-kpi-foot">approved projects with funding</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Total allocated fund</div>
            <div class="ra-kpi-value" id="overviewAllocatedFund">—</div>
            <div class="ra-kpi-foot">sum of allocated funding</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Active funded campuses</div>
            <div class="ra-kpi-value" id="overviewFundedCampuses">—</div>
            <div class="ra-kpi-foot">with at least one funded project</div>
        </div>
    </div>
</div>

<script>
    /**
     * Overview panel — Publications lead indicators.
     * Pulls a slim publications snapshot from /api/publications/dashboard
     * so the Overview tab shows real numbers instead of static placeholders.
     */
    (function () {
        const API_BASE = window.RESEARCH_API_BASE ||
            '{{ rtrim(config('services.research_api.url', 'http://127.0.0.1:8001'), '/') }}';

        let overviewPubLoaded = false;

        const fmtInt = (n) => Number(n ?? 0).toLocaleString('en-US');
        const fmtPct = (n) => (n === null || n === undefined) ? '—' : `${Number(n).toFixed(1)}%`;

        function setText(id, value) {
            const el = document.getElementById(id);
            if (el) el.textContent = value;
        }

        function buildUrl(year) {
            const url = new URL(`${API_BASE}/api/publications/dashboard`);
            if (year) url.searchParams.set('year', year);
            return url.toString();
        }

        async function loadOverviewPublications(year = '') {
            try {
                const res = await fetch(buildUrl(year), { headers: { Accept: 'application/json' } });
                if (!res.ok) throw new Error(`Publications service responded with ${res.status}`);
                const data = await res.json();

                setText('overviewTotalOutputs', fmtInt(data.total_outputs?.[0]?.total_outputs));
                setText('overviewCompletedOutputs', fmtInt(data.completed_outputs?.[0]?.completed_outputs));
                setText('overviewOutputCompletionRate', fmtPct(data.completion_rate?.[0]?.completion_rate_pct));
                setText('overviewOutputCampuses', fmtInt(data.active_campuses?.[0]?.active_campuses));

                overviewPubLoaded = true;
            } catch (err) {
                console.error(err);
                ['overviewTotalOutputs', 'overviewCompletedOutputs', 'overviewOutputCompletionRate', 'overviewOutputCampuses']
                    .forEach((id) => setText(id, '—'));
            }
        }

        // Reload when the shared year filter changes, but only once this
        // panel has actually loaded the data at least once.
        document.getElementById('yearFilter')?.addEventListener('change', (e) => {
            if (overviewPubLoaded) {
                loadOverviewPublications(e.target.value);
            }
        });

        // Reload when the Overview tab is clicked, in case the cards were
        // stale from a year filter changed elsewhere while hidden.
        document.querySelectorAll('.ra-tab').forEach((tab) => {
            tab.addEventListener('click', () => {
                if (tab.dataset.tab === 'overview') {
                    const year = document.getElementById('yearFilter')?.value || '';
                    loadOverviewPublications(year);
                }
            });
        });

        // Overview is the default active panel on first paint, so load
        // immediately rather than waiting for a tab click.
        document.addEventListener('DOMContentLoaded', () => {
            const panel = document.getElementById('overviewDashboard');
            if (panel && panel.classList.contains('is-active')) {
                loadOverviewPublications('');
            }
        });
    })();
</script>