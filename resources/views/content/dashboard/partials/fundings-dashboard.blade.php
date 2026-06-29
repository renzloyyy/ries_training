{{--
    Fundings dashboard panel.
    Mirrors the visual design of the Publications panel: KPI strip, donut +
    bar + area charts via ApexCharts, signal-bar list for campus funding
    share, and a grouped campus → program breakdown table.
    Data source: FastAPI /api/funding/dashboard (soulsuedu_ries DB).
--}}

<div class="row g-3 mb-3">
    <div class="col-sm-6 col-lg-6">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Total funded projects</div>
            <div class="ra-kpi-value" id="fundKpiTotalProjects"><span class="ra-skel"></span></div>
            <div class="ra-kpi-foot">approved projects with funding</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-6">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Total allocated fund</div>
            <div class="ra-kpi-value" id="fundKpiTotalAllocated"><span class="ra-skel"></span></div>
            <div class="ra-kpi-foot">sum of allocated funding</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    {{-- Funding by program category --}}
    <div class="col-lg-4">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Funding by category</h2>
                    <div class="ra-card-sub">allocated fund by research category</div>
                </div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartFundCategory" style="min-height:230px;"></div>
            </div>
        </div>
    </div>

    {{-- Funding by campus --}}
    <div class="col-lg-8">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Funding by campus</h2>
                    <div class="ra-card-sub">allocated fund grouped by campus</div>
                </div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartFundCampus" style="min-height:230px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    {{-- Funding over time --}}
    <div class="col-lg-6">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Funding over time</h2>
                    <div class="ra-card-sub">allocated fund by year and month</div>
                </div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartFundTrend" style="min-height:260px;"></div>
            </div>
        </div>
    </div>

    {{-- Funding by research format --}}
    <div class="col-lg-6">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Funding by research format</h2>
                    <div class="ra-card-sub">mix of study types receiving funding</div>
                </div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartFundFormat" style="min-height:260px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    {{-- Funding by agency --}}
    <div class="col-lg-5">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Funding by agency</h2>
                    <div class="ra-card-sub">where allocated funds originate</div>
                </div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartFundAgency" style="min-height:300px;"></div>
            </div>
        </div>
    </div>

    {{-- Signature element: campus share of total allocated fund --}}
    <div class="col-lg-7">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Campus share of allocated fund</h2>
                    <div class="ra-card-sub"></div>
                </div>
            </div>
            <div class="px-3" id="fundFunnelList">
                <div class="ra-empty">loading campus data&hellip;</div>
            </div>
            <div class="ra-legend">
                <span><i style="background:var(--ra-approved)"></i> Allocated fund share</span>
            </div>
        </div>
    </div>
</div>

{{-- Campus x Program detail table --}}
<div class="row g-3 mb-3">
    <div class="col-12">
        <div class="ra-card">
            <div class="ra-card-head pb-2" style="align-items:center;">
                <div>
                    <h2 class="ra-card-title">Campus &amp; program breakdown</h2>
                    <div class="ra-card-sub">every program, grouped by campus</div>
                </div>
                <input type="search" id="fundProgramFilter" class="form-control form-control-sm ra-search"
                    style="max-width:220px;" placeholder="filter campus or program&hellip;" />
            </div>
            <div class="px-3 pb-3" style="max-height:420px; overflow-y:auto;">
                <table class="table ra-table mb-0" id="fundProgramTable">
                    <thead>
                        <tr>
                            <th>Campus</th>
                            <th>Program</th>
                            <th class="text-end ra-metric-head ra-head-total">
                                <span class="ra-head-label"><span class="ra-head-dot">+</span>Funded
                                    projects</span>
                            </th>
                            <th class="text-end ra-metric-head ra-head-completed">
                                <span class="ra-head-label"><span class="ra-head-dot">+</span>Allocated
                                    fund</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="fundProgramTableBody">
                        <tr>
                            <td colspan="4" class="ra-empty">loading&hellip;</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    (function () {
        const API_BASE = window.RESEARCH_API_BASE ||
            '{{ rtrim(config('services.research_api.url', 'http://127.0.0.1:8001'), '/') }}';

        const pageEl = document.getElementById('raPage');
        const MONO = "'JetBrains Mono', 'IBM Plex Mono', ui-monospace, monospace";
        const fundChartInstances = {};
        let fundLoaded = false;
        let fundProgramRowsCache = [];

        const fmtInt = (n) => Number(n ?? 0).toLocaleString('en-US');
        const fmtCurrency = (n) => `₱${Number(n ?? 0).toLocaleString('en-US', { maximumFractionDigits: 0 })}`;
        const fmtPct = (n) => (n === null || n === undefined) ? '—' : `${Number(n).toFixed(1)}%`;
        const escapeHtml = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
        }[c]));

        function cssVar(name) {
            if (!pageEl) return '';
            return getComputedStyle(pageEl).getPropertyValue(name).trim();
        }

        function currentTheme() {
            return pageEl && pageEl.dataset.theme === 'light' ? 'light' : 'dark';
        }

        function baseChartOptions(overrides) {
            const textDim = cssVar('--ra-text-dim');
            const line = cssVar('--ra-line');
            return Object.assign({
                chart: {
                    fontFamily: 'Public Sans, sans-serif',
                    background: 'transparent',
                    toolbar: { show: false },
                    animations: { easing: 'easeinout', speed: 500 },
                },
                theme: { mode: currentTheme() },
                colors: [cssVar('--ra-approved'), cssVar('--ra-pending'), '#6E84A8', '#A98FC4'],
                dataLabels: { enabled: false },
                grid: { borderColor: line, strokeDashArray: 3 },
                tooltip: { theme: currentTheme() },
                legend: { labels: { colors: textDim } },
                states: {
                    hover: { filter: { type: 'none' } },
                    active: { filter: { type: 'none' } },
                },
            }, overrides);
        }

        function buildFundDashboardUrl(year) {
            const url = new URL(`${API_BASE}/api/funding/dashboard`);
            if (year) url.searchParams.set('year', year);
            return url.toString();
        }

        async function fetchFundDashboard(year = '') {
            const res = await fetch(buildFundDashboardUrl(year), {
                headers: { Accept: 'application/json' },
            });
            if (!res.ok) {
                const text = await res.text().catch(() => '');
                throw new Error(`Funding service responded with ${res.status}: ${text}`);
            }
            return res.json();
        }

        function destroyFundChart(key) {
            if (fundChartInstances[key]) {
                fundChartInstances[key].destroy();
                delete fundChartInstances[key];
            }
        }

        function renderFundChart(key, elementId, options) {
            destroyFundChart(key);
            const el = document.getElementById(elementId);
            if (!el) return;
            const chart = new ApexCharts(el, options);
            fundChartInstances[key] = chart;
            chart.render();
        }

        function setText(id, value) {
            const el = document.getElementById(id);
            if (el) el.textContent = value;
        }

        function renderFundKpis(data) {
            setText('fundKpiTotalProjects', fmtInt(data.total_funded_projects?.[0]?.total_funded_projects));
            setText('fundKpiTotalAllocated', fmtCurrency(data.total_allocated_fund?.[0]?.total_allocated_fund));
        }

        // Feeds from `funding_by_category` — grouped by Category ONLY.
        // Do NOT swap this for funding_by_format: that query groups by
        // (Category, ResearchFormat), which produces several rows that
        // share the same category label and renders as duplicate,
        // same-named donut slices.
        function renderFundCategoryChart(rows) {
            destroyFundChart('category');
            const el = document.getElementById('chartFundCategory');
            if (!el) return;
            if (!rows || !rows.length) {
                el.innerHTML = '<div class="ra-empty">no category funding data yet</div>';
                return;
            }
            el.innerHTML = '';
            renderFundChart('category', 'chartFundCategory', baseChartOptions({
                chart: { type: 'donut', height: 230 },
                labels: rows.map((r) => r.program_category),
                series: rows.map((r) => Number(r.total_allocated_fund || 0)),
                // Put the callouts in the side legend so category names remain
                // readable without crowding the donut slices.
                dataLabels: {
                    enabled: false,
                },
                stroke: { colors: [cssVar('--ra-panel')], width: 2 },
                legend: {
                    position: 'right',
                    fontSize: '12px',
                    labels: { colors: cssVar('--ra-text-dim') },
                    formatter: (seriesName, opts) => {
                        const pct = Number(opts.w.globals.seriesPercent[opts.seriesIndex] || 0);
                        return `${seriesName} (${Math.round(pct)}%)`;
                    },
                },
                tooltip: { y: { formatter: (v) => fmtCurrency(v) } },
            }));
        }

        function renderFundCampusChart(rows) {
            destroyFundChart('campus');
            const el = document.getElementById('chartFundCampus');
            if (!el) return;
            if (!rows || !rows.length) {
                el.innerHTML = '<div class="ra-empty">no campus funding data yet</div>';
                return;
            }
            el.innerHTML = '';
            renderFundChart('campus', 'chartFundCampus', baseChartOptions({
                chart: { type: 'bar', height: 230 },
                plotOptions: { bar: { borderRadius: 2, columnWidth: '50%' } },
                series: [{ name: 'Allocated fund', data: rows.map((r) => Number(r.total_allocated_fund || 0)) }],
                xaxis: {
                    categories: rows.map((r) => r.campus_name),
                    labels: {
                        style: { fontSize: '11px', colors: cssVar('--ra-text-faint'), fontFamily: MONO },
                        rotate: -45,
                    },
                    axisBorder: { color: cssVar('--ra-line') },
                    axisTicks: { color: cssVar('--ra-line') },
                },
                yaxis: {
                    labels: {
                        style: { colors: cssVar('--ra-text-faint'), fontFamily: MONO },
                        formatter: (v) => fmtCurrency(v),
                    },
                },
                tooltip: { y: { formatter: (v) => fmtCurrency(v) } },
                colors: ['#6E84A8'],
            }));
        }

        function renderFundTrendChart(rows) {
            destroyFundChart('trend');
            const el = document.getElementById('chartFundTrend');
            if (!el) return;
            if (!rows || !rows.length) {
                el.innerHTML = '<div class="ra-empty">no funding history yet</div>';
                return;
            }
            el.innerHTML = '';
            const monthNames = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const categories = rows.map((r) => `${monthNames[r.mo] || r.mo} ${r.yr}`);
            renderFundChart('trend', 'chartFundTrend', baseChartOptions({
                chart: { type: 'area', height: 260 },
                series: [{ name: 'Allocated fund', data: rows.map((r) => Number(r.total_allocated_fund || 0)) }],
                xaxis: {
                    categories,
                    tickAmount: 8,
                    labels: { style: { fontSize: '11px', colors: cssVar('--ra-text-faint'), fontFamily: MONO } },
                    axisBorder: { color: cssVar('--ra-line') },
                    axisTicks: { color: cssVar('--ra-line') },
                },
                yaxis: {
                    labels: {
                        style: { colors: cssVar('--ra-text-faint'), fontFamily: MONO },
                        formatter: (v) => fmtCurrency(v),
                    },
                },
                tooltip: { y: { formatter: (v) => fmtCurrency(v) } },
                stroke: { curve: 'smooth', width: 2 },
                fill: {
                    type: 'gradient',
                    gradient: { shadeIntensity: 1, opacityFrom: .35, opacityTo: 0, stops: [0, 95, 100] },
                },
                colors: [cssVar('--ra-approved')],
            }));
        }

        // Feeds from `funding_by_format` — grouped by (Category, ResearchFormat).
        // Labelled by research_format so multiple categories sharing a
        // format don't collide into a single bar.
        function renderFundFormatChart(rows) {
            destroyFundChart('format');
            const el = document.getElementById('chartFundFormat');
            if (!el) return;
            if (!rows || !rows.length) {
                el.innerHTML = '<div class="ra-empty">no format funding data yet</div>';
                return;
            }
            el.innerHTML = '';
            renderFundChart('format', 'chartFundFormat', baseChartOptions({
                chart: { type: 'bar', height: 260 },
                plotOptions: { bar: { horizontal: true, borderRadius: 2, barHeight: '55%' } },
                series: [{ name: 'Allocated fund', data: rows.map((r) => Number(r.total_allocated_fund || 0)) }],
                xaxis: {
                    categories: rows.map((r) => r.research_format),
                    labels: {
                        style: { colors: cssVar('--ra-text-faint'), fontFamily: MONO, fontSize: '11px' },
                        formatter: (v) => fmtCurrency(v),
                    },
                    axisBorder: { color: cssVar('--ra-line') },
                    axisTicks: { color: cssVar('--ra-line') },
                },
                yaxis: { labels: { style: { colors: cssVar('--ra-text-dim'), fontSize: '12px' } } },
                tooltip: { x: { formatter: (v) => fmtCurrency(v) } },
                colors: [cssVar('--ra-approved')],
            }));
        }

        function renderFundAgencyChart(rows) {
            destroyFundChart('agency');
            const el = document.getElementById('chartFundAgency');
            if (!el) return;
            if (!rows || !rows.length) {
                el.innerHTML = '<div class="ra-empty">no agency funding data yet</div>';
                return;
            }
            el.innerHTML = '';
            renderFundChart('agency', 'chartFundAgency', baseChartOptions({
                chart: { type: 'donut', height: 300 },
                labels: rows.map((r) => r.funding_agency),
                series: rows.map((r) => Number(r.total_allocated_fund || 0)),
                // Put agency callouts into the side legend so the chart stays
                // readable even when agency names are long.
                dataLabels: {
                    enabled: false,
                },
                stroke: { colors: [cssVar('--ra-panel')], width: 2 },
                legend: {
                    position: 'right',
                    fontSize: '13px',
                    labels: { colors: cssVar('--ra-text-dim') },
                    formatter: (seriesName, opts) => {
                        const pct = Number(opts.w.globals.seriesPercent[opts.seriesIndex] || 0);
                        return `${seriesName} (${Math.round(pct)}%)`;
                    },
                },
                tooltip: { y: { formatter: (v) => fmtCurrency(v) } },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    color: cssVar('--ra-text-dim'),
                                    formatter: (w) => fmtCurrency(
                                        w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                    ),
                                },
                                value: { color: cssVar('--ra-text'), fontFamily: MONO },
                            },
                        },
                    },
                },
            }));
        }

        function renderFundFunnel(rows) {
            const container = document.getElementById('fundFunnelList');
            if (!container) return;
            if (!rows || !rows.length) {
                container.innerHTML = '<div class="ra-empty">no campus data yet</div>';
                return;
            }
            const grandTotal = rows.reduce((sum, r) => sum + Number(r.total_allocated_fund || 0), 0);
            const withShare = rows.map((r) => {
                const allocated = Number(r.total_allocated_fund || 0);
                const share = grandTotal > 0 ? (allocated / grandTotal) * 100 : 0;
                return { ...r, fund_share: share, total_allocated_fund: allocated };
            }).sort((a, b) => b.fund_share - a.fund_share);

            container.innerHTML = withShare.map((r) => `
                <div class="ra-funnel-row">
                    <div class="ra-funnel-name" title="${escapeHtml(r.campus_name)}">${escapeHtml(r.campus_name)}</div>
                    <div class="ra-funnel-track">
                        <div class="ra-funnel-fill" data-pct="${r.fund_share}"></div>
                    </div>
                    <div class="ra-funnel-pct">${fmtCurrency(r.total_allocated_fund)}</div>
                </div>
            `).join('');
            requestAnimationFrame(() => {
                container.querySelectorAll('.ra-funnel-fill').forEach((el) => {
                    el.style.width = `${el.dataset.pct}%`;
                });
            });
        }

        function groupProgramsByCampus(rows) {
            const campusMap = new Map();
            rows.forEach((row) => {
                const campusName = row.campus_name || 'Unknown campus';
                if (!campusMap.has(campusName)) {
                    campusMap.set(campusName, {
                        campus_name: campusName,
                        funded_projects: 0,
                        total_allocated_fund: 0,
                        programs: [],
                    });
                }
                const group = campusMap.get(campusName);
                group.funded_projects += Number(row.funded_projects || 0);
                group.total_allocated_fund += Number(row.total_allocated_fund || 0);
                group.programs.push(row);
            });
            return Array.from(campusMap.values()).sort((a, b) => {
                if (a.campus_name === 'MAIN CAMPUS') return -1;
                if (b.campus_name === 'MAIN CAMPUS') return 1;
                return 0;
            });
        }

        function paintFundProgramTable(rows) {
            const tbody = document.getElementById('fundProgramTableBody');
            if (!tbody) return;
            if (!rows.length) {
                tbody.innerHTML = '<tr><td colspan="4" class="ra-empty">no matching programs</td></tr>';
                return;
            }
            const groupedRows = groupProgramsByCampus(rows);
            tbody.innerHTML = groupedRows.map((group) => `
                <tr class="ra-campus-summary">
                    <td>
                        <div class="ra-campus-name">${escapeHtml(group.campus_name)}</div>
                        <div class="ra-campus-meta">${fmtInt(group.programs.length)} department${group.programs.length === 1 ? '' : 's'}</div>
                    </td>
                    <td class="ra-campus-meta">Campus total</td>
                    <td class="ra-campus-total">${fmtInt(group.funded_projects)}</td>
                    <td class="ra-campus-total">${fmtCurrency(group.total_allocated_fund)}</td>
                </tr>
                ${group.programs.map((program) => `
                    <tr class="ra-program-row">
                        <td>Department</td>
                        <td class="ra-program-name">${escapeHtml(program.department)}</td>
                        <td class="ra-metric ra-metric-total">${fmtInt(program.funded_projects)}</td>
                        <td class="ra-metric ra-metric-completed">${fmtCurrency(program.total_allocated_fund)}</td>
                    </tr>
                `).join('')}
            `).join('');
        }

        function renderFundProgramTable(rows) {
            fundProgramRowsCache = rows || [];
            paintFundProgramTable(fundProgramRowsCache);
        }

        document.getElementById('fundProgramFilter')?.addEventListener('input', (e) => {
            const q = e.target.value.trim().toLowerCase();
            if (!q) {
                paintFundProgramTable(fundProgramRowsCache);
                return;
            }
            paintFundProgramTable(fundProgramRowsCache.filter((r) =>
                (r.campus_name || '').toLowerCase().includes(q) ||
                (r.department || '').toLowerCase().includes(q)
            ));
        });

        function showFundError(message) {
            console.error('[Funding panel]', message);
            const globalError = document.getElementById('raGlobalError');
            if (globalError) {
                globalError.textContent = message;
                globalError.style.display = 'block';
            }
            // Make every chart/area show the error instead of staying blank.
            ['chartFundCategory', 'chartFundCampus', 'chartFundTrend',
             'chartFundFormat', 'chartFundAgency'].forEach((id) => {
                const el = document.getElementById(id);
                if (el) el.innerHTML = `<div class="ra-empty">⚠ ${escapeHtml(message)}</div>`;
            });
            const funnel = document.getElementById('fundFunnelList');
            if (funnel) funnel.innerHTML = `<div class="ra-empty">⚠ ${escapeHtml(message)}</div>`;
            const tbody = document.getElementById('fundProgramTableBody');
            if (tbody) tbody.innerHTML = `<tr><td colspan="4" class="ra-empty">⚠ ${escapeHtml(message)}</td></tr>`;
            ['fundKpiTotalProjects', 'fundKpiTotalAllocated'].forEach((id) => setText(id, '—'));
        }

        async function loadFunding(year = '') {
            try {
                const data = await fetchFundDashboard(year);

                renderFundKpis(data);
                renderFundCategoryChart(data.funding_by_category || []);
                renderFundCampusChart(data.funding_by_campus || []);
                renderFundTrendChart(data.funding_monthly_trend || []);
                renderFundFormatChart(data.funding_by_format || []);
                renderFundAgencyChart(data.funding_by_agency || []);
                renderFundFunnel(data.funding_by_campus || []);
                renderFundProgramTable(data.funding_by_department || []);

                const globalError = document.getElementById('raGlobalError');
                if (globalError) globalError.style.display = 'none';

                fundLoaded = true;
            } catch (err) {
                showFundError(
                    `Couldn't load funding data for ${year || 'all years'} from ${API_BASE}: ${err.message}`
                );
            }
        }

        document.getElementById('yearFilter')?.addEventListener('change', (e) => {
            if (fundLoaded) {
                loadFunding(e.target.value);
            }
        });

        document.querySelectorAll('.ra-tab').forEach((tab) => {
            tab.addEventListener('click', () => {
                if (tab.dataset.tab === 'fundings') {
                    const year = document.getElementById('yearFilter')?.value || '';
                    loadFunding(year);
                }
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const panel = document.getElementById('fundingsDashboard');
            if (panel && panel.classList.contains('is-active')) {
                loadFunding('');
            }
        });

        // In case this script runs after DOMContentLoaded already fired
        // (common when Blade partials are injected late), check immediately too.
        if (document.readyState !== 'loading') {
            const panel = document.getElementById('fundingsDashboard');
            if (panel && panel.classList.contains('is-active')) {
                loadFunding('');
            }
        }
    })();
</script>
