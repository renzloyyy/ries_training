{{--
    Publications / Research Outputs dashboard panel.
    Mirrors the visual design of the Proposals panel: KPI strip, donut +
    bar + area charts via ApexCharts, signal-bar list for campus rates,
    and a grouped campus → program breakdown table.
    Data source: FastAPI /api/publications/dashboard (soulsuedu_ries DB).
--}}

<div class="row g-3 mb-3">
    <div class="col-sm-6 col-lg-3">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Total outputs</div>
            <div class="ra-kpi-value" id="pubKpiTotal"><span class="ra-skel"></span></div>
            <div class="ra-kpi-foot">all research outputs</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Completion rate</div>
            <div class="ra-kpi-value" id="pubKpiCompletionRate"><span class="ra-skel"></span></div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Active campuses</div>
            <div class="ra-kpi-value" id="pubKpiCampuses"><span class="ra-skel"></span></div>
            <div class="ra-kpi-foot">with at least one output</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Completed outputs</div>
            <div class="ra-kpi-value" id="pubKpiCompleted"><span class="ra-skel"></span></div>
            <div class="ra-kpi-foot">based on complete count</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    {{-- Output category --}}
    <div class="col-lg-4">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Output category</h2>
                    <div class="ra-card-sub">journal, conference, book, or other types</div>
                </div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartPubCategory" style="min-height:230px;"></div>
            </div>
        </div>
    </div>

    {{-- SDG alignment --}}
    <div class="col-lg-8">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">SDG alignment</h2>
                    <div class="ra-card-sub">output counts by Sustainable Development Goal</div>
                </div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartPubSdg" style="min-height:230px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    {{-- Monthly trend --}}
    <div class="col-lg-6">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Outputs over time</h2>
                    <div class="ra-card-sub">by year and month</div>
                </div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartPubTrend" style="min-height:260px;"></div>
            </div>
        </div>
    </div>

    {{-- Research format --}}
    <div class="col-lg-6">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Research format</h2>
                    <div class="ra-card-sub">mix of study types</div>
                </div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartPubFormat" style="min-height:260px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    {{-- Paper status --}}
    <div class="col-lg-5">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Paper status</h2>
                    <div class="ra-card-sub">where each output sits in publication</div>
                </div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartPubPaperStatus" style="min-height:300px;"></div>
            </div>
        </div>
    </div>

    {{-- Signature element: completion signal bars by campus --}}
    <div class="col-lg-7">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Completion rate by campus</h2>
                    <div class="ra-card-sub"></div>
                </div>
            </div>
            <div class="px-3" id="pubFunnelList">
                <div class="ra-empty">loading campus data&hellip;</div>
            </div>
            <div class="ra-legend">
                <span><i style="background:var(--ra-approved)"></i> Completed</span>
                <span><i style="background:var(--ra-line); border:1px solid var(--ra-text-faint)"></i>
                    Pending</span>
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
                <input type="search" id="pubProgramFilter" class="form-control form-control-sm ra-search"
                    style="max-width:220px;" placeholder="filter campus or program&hellip;" />
            </div>
            <div class="px-3 pb-3" style="max-height:420px; overflow-y:auto;">
                <table class="table ra-table mb-0" id="pubProgramTable">
                    <thead>
                        <tr>
                            <th>Campus</th>
                            <th>Program</th>
                            <th class="text-end ra-metric-head ra-head-total">
                                <span class="ra-head-label"><span class="ra-head-dot">+</span>Total
                                    outputs</span>
                            </th>
                            <th class="text-end ra-metric-head ra-head-completed">
                                <span class="ra-head-label"><span class="ra-head-dot">+</span>Completed
                                    outputs</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="pubProgramTableBody">
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
    /**
     * Publications panel.
     * Same chart-building conventions as the Proposals panel script:
     * cssVar()-driven palette, baseChartOptions(), destroy-then-rebuild
     * ApexCharts instances, and a grouped campus/program table with its
     * own local search filter. Scoped to /api/publications/dashboard.
     */
    (function () {
        const API_BASE = window.RESEARCH_API_BASE ||
            '{{ rtrim(config('services.research_api.url', 'http://127.0.0.1:8001'), '/') }}';

        const pageEl = document.getElementById('raPage');
        const MONO = "'JetBrains Mono', 'IBM Plex Mono', ui-monospace, monospace";
        const pubChartInstances = {};
        let pubLoaded = false;
        let pubProgramRowsCache = [];

        const fmtInt = (n) => Number(n ?? 0).toLocaleString('en-US');
        const fmtPct = (n) => (n === null || n === undefined) ? '—' : `${Number(n).toFixed(1)}%`;
        const escapeHtml = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
        }[c]));

        function cssVar(name) {
            return getComputedStyle(pageEl).getPropertyValue(name).trim();
        }

        function currentTheme() {
            return pageEl.dataset.theme === 'light' ? 'light' : 'dark';
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

        function buildPubDashboardUrl(year) {
            const url = new URL(`${API_BASE}/api/publications/dashboard`);
            if (year) url.searchParams.set('year', year);
            return url.toString();
        }

        async function fetchPubDashboard(year = '') {
            const res = await fetch(buildPubDashboardUrl(year), {
                headers: { Accept: 'application/json' },
            });
            if (!res.ok) throw new Error(`Publications service responded with ${res.status}`);
            return res.json();
        }

        function destroyPubChart(key) {
            if (pubChartInstances[key]) {
                pubChartInstances[key].destroy();
                delete pubChartInstances[key];
            }
        }

        function renderPubChart(key, elementId, options) {
            destroyPubChart(key);
            const chart = new ApexCharts(document.getElementById(elementId), options);
            pubChartInstances[key] = chart;
            chart.render();
        }

        function setText(id, value) {
            const el = document.getElementById(id);
            if (el) el.textContent = value;
        }

        function renderPubKpis(data) {
            setText('pubKpiTotal', fmtInt(data.total_outputs?.[0]?.total_outputs));
            setText('pubKpiCompleted', fmtInt(data.completed_outputs?.[0]?.completed_outputs));
            setText('pubKpiCompletionRate', fmtPct(data.completion_rate?.[0]?.completion_rate_pct));
            setText('pubKpiCampuses', fmtInt(data.active_campuses?.[0]?.active_campuses));
        }

        function renderPubCategoryChart(rows) {
            destroyPubChart('category');
            const el = document.getElementById('chartPubCategory');
            if (!rows || !rows.length) {
                el.innerHTML = '<div class="ra-empty">no category data yet</div>';
                return;
            }
            el.innerHTML = '';
            renderPubChart('category', 'chartPubCategory', baseChartOptions({
                chart: { type: 'donut', height: 230 },
                labels: rows.map((r) => r.category),
                series: rows.map((r) => Number(r.total_outputs || 0)),
                stroke: { colors: [cssVar('--ra-panel')], width: 2 },
                legend: {
                    position: 'bottom',
                    fontSize: '11px',
                    labels: { colors: cssVar('--ra-text-dim') },
                },
            }));
        }

        function renderPubSdgChart(rows) {
            destroyPubChart('sdg');
            const el = document.getElementById('chartPubSdg');
            if (!rows || !rows.length) {
                el.innerHTML = '<div class="ra-empty">no SDG data yet</div>';
                return;
            }
            el.innerHTML = '';
            renderPubChart('sdg', 'chartPubSdg', baseChartOptions({
                chart: { type: 'bar', height: 230 },
                plotOptions: { bar: { borderRadius: 2, columnWidth: '50%' } },
                series: [{ name: 'Outputs', data: rows.map((r) => Number(r.total_outputs || 0)) }],
                xaxis: {
                    categories: rows.map((r) => r.sdg_name),
                    labels: {
                        style: { fontSize: '9px', colors: cssVar('--ra-text-faint'), fontFamily: MONO },
                        rotate: -45,
                    },
                    axisBorder: { color: cssVar('--ra-line') },
                    axisTicks: { color: cssVar('--ra-line') },
                },
                yaxis: { labels: { style: { colors: cssVar('--ra-text-faint'), fontFamily: MONO } } },
                colors: ['#6E84A8'],
            }));
        }

        function renderPubTrendChart(rows) {
            destroyPubChart('trend');
            const el = document.getElementById('chartPubTrend');
            if (!rows || !rows.length) {
                el.innerHTML = '<div class="ra-empty">no submission history yet</div>';
                return;
            }
            el.innerHTML = '';
            const monthNames = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const categories = rows.map((r) => `${monthNames[r.mo] || r.mo} ${r.yr}`);
            renderPubChart('trend', 'chartPubTrend', baseChartOptions({
                chart: { type: 'area', height: 260 },
                series: [{ name: 'Outputs', data: rows.map((r) => Number(r.total_outputs || 0)) }],
                xaxis: {
                    categories,
                    tickAmount: 8,
                    labels: { style: { fontSize: '10px', colors: cssVar('--ra-text-faint'), fontFamily: MONO } },
                    axisBorder: { color: cssVar('--ra-line') },
                    axisTicks: { color: cssVar('--ra-line') },
                },
                yaxis: { labels: { style: { colors: cssVar('--ra-text-faint'), fontFamily: MONO } } },
                stroke: { curve: 'smooth', width: 2 },
                fill: {
                    type: 'gradient',
                    gradient: { shadeIntensity: 1, opacityFrom: .35, opacityTo: 0, stops: [0, 95, 100] },
                },
                colors: [cssVar('--ra-approved')],
            }));
        }

        function renderPubFormatChart(rows) {
            destroyPubChart('format');
            const el = document.getElementById('chartPubFormat');
            if (!rows || !rows.length) {
                el.innerHTML = '<div class="ra-empty">no format data yet</div>';
                return;
            }
            el.innerHTML = '';
            renderPubChart('format', 'chartPubFormat', baseChartOptions({
                chart: { type: 'bar', height: 260 },
                plotOptions: { bar: { horizontal: true, borderRadius: 2, barHeight: '55%' } },
                series: [{ name: 'Outputs', data: rows.map((r) => Number(r.total_outputs || 0)) }],
                xaxis: {
                    categories: rows.map((r) => r.research_format),
                    labels: { style: { colors: cssVar('--ra-text-faint'), fontFamily: MONO, fontSize: '10px' } },
                    axisBorder: { color: cssVar('--ra-line') },
                    axisTicks: { color: cssVar('--ra-line') },
                },
                yaxis: { labels: { style: { colors: cssVar('--ra-text-dim'), fontSize: '11px' } } },
                colors: [cssVar('--ra-approved')],
            }));
        }

        function renderPubPaperStatusChart(rows) {
            destroyPubChart('paperStatus');
            const el = document.getElementById('chartPubPaperStatus');
            if (!rows || !rows.length) {
                el.innerHTML = '<div class="ra-empty">no paper status data yet</div>';
                return;
            }
            el.innerHTML = '';
            renderPubChart('paperStatus', 'chartPubPaperStatus', baseChartOptions({
                chart: { type: 'donut', height: 300 },
                labels: rows.map((r) => r.paper_status),
                series: rows.map((r) => Number(r.total_outputs || 0)),
                stroke: { colors: [cssVar('--ra-panel')], width: 2 },
                legend: {
                    position: 'bottom',
                    fontSize: '12px',
                    labels: { colors: cssVar('--ra-text-dim') },
                },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                total: { show: true, label: 'Total', color: cssVar('--ra-text-dim') },
                                value: { color: cssVar('--ra-text'), fontFamily: MONO },
                            },
                        },
                    },
                },
            }));
        }

        function renderPubFunnel(rows) {
            const container = document.getElementById('pubFunnelList');
            if (!rows || !rows.length) {
                container.innerHTML = '<div class="ra-empty">no campus data yet</div>';
                return;
            }
            // Same signal-bar pattern as the Proposals panel's approval-rate
            // list, but driven off completed/total outputs per campus.
            const withRate = rows.map((r) => {
                const total = Number(r.total_outputs || 0);
                const completed = Number(r.completed_outputs || 0);
                const rate = total > 0 ? (completed / total) * 100 : 0;
                return { ...r, completion_rate: rate };
            }).sort((a, b) => b.completion_rate - a.completion_rate);

            container.innerHTML = withRate.map((r) => `
                <div class="ra-funnel-row">
                    <div class="ra-funnel-name" title="${escapeHtml(r.campus_name)}">${escapeHtml(r.campus_name)}</div>
                    <div class="ra-funnel-track">
                        <div class="ra-funnel-fill" data-pct="${r.completion_rate}"></div>
                    </div>
                    <div class="ra-funnel-pct">${fmtPct(r.completion_rate)}</div>
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
                        total_outputs: 0,
                        completed_outputs: 0,
                        programs: [],
                    });
                }
                const group = campusMap.get(campusName);
                group.total_outputs += Number(row.total_outputs || 0);
                group.completed_outputs += Number(row.completed_outputs || 0);
                group.programs.push(row);
            });
            return Array.from(campusMap.values()).sort((a, b) => {
                if (a.campus_name === 'MAIN CAMPUS') return -1;
                if (b.campus_name === 'MAIN CAMPUS') return 1;
                return 0;
            });
        }

        function paintPubProgramTable(rows) {
            const tbody = document.getElementById('pubProgramTableBody');
            if (!rows.length) {
                tbody.innerHTML = '<tr><td colspan="4" class="ra-empty">no matching programs</td></tr>';
                return;
            }
            const groupedRows = groupProgramsByCampus(rows);
            tbody.innerHTML = groupedRows.map((group) => `
                <tr class="ra-campus-summary">
                    <td>
                        <div class="ra-campus-name">${escapeHtml(group.campus_name)}</div>
                        <div class="ra-campus-meta">${fmtInt(group.programs.length)} program${group.programs.length === 1 ? '' : 's'}</div>
                    </td>
                    <td class="ra-campus-meta">Campus total outputs</td>
                    <td class="ra-campus-total">${fmtInt(group.total_outputs)}</td>
                    <td class="ra-campus-total">${fmtInt(group.completed_outputs)}</td>
                </tr>
                ${group.programs.map((program) => `
                    <tr class="ra-program-row">
                        <td>Program</td>
                        <td class="ra-program-name">${escapeHtml(program.program_name)}</td>
                        <td class="ra-metric ra-metric-total">${fmtInt(program.total_outputs)}</td>
                        <td class="ra-metric ra-metric-completed">${fmtInt(program.completed_outputs)}</td>
                    </tr>
                `).join('')}
            `).join('');
        }

        function renderPubProgramTable(rows) {
            pubProgramRowsCache = rows || [];
            paintPubProgramTable(pubProgramRowsCache);
        }

        document.getElementById('pubProgramFilter')?.addEventListener('input', (e) => {
            const q = e.target.value.trim().toLowerCase();
            if (!q) {
                paintPubProgramTable(pubProgramRowsCache);
                return;
            }
            paintPubProgramTable(pubProgramRowsCache.filter((r) =>
                (r.campus_name || '').toLowerCase().includes(q) ||
                (r.program_name || '').toLowerCase().includes(q)
            ));
        });

        async function loadPublications(year = '') {
            try {
                const data = await fetchPubDashboard(year);

                renderPubKpis(data);
                renderPubCategoryChart(data.outputs_by_category || []);
                renderPubSdgChart(data.outputs_by_sdg || []);
                renderPubTrendChart(data.monthly_trend || []);
                renderPubFormatChart(data.outputs_by_format || []);
                renderPubPaperStatusChart(data.outputs_by_paper_status || []);
                renderPubFunnel(data.outputs_by_campus || []);
                renderPubProgramTable(data.outputs_by_program || []);

                pubLoaded = true;
            } catch (err) {
                console.error(err);
                const globalError = document.getElementById('raGlobalError');
                if (globalError) {
                    globalError.textContent =
                        `Couldn't load publications data for ${year || 'all years'} from ${API_BASE}. Confirm the FastAPI app is running.`;
                    globalError.style.display = 'block';
                }
                ['pubKpiTotal', 'pubKpiCompleted', 'pubKpiCompletionRate', 'pubKpiCampuses']
                    .forEach((id) => setText(id, '—'));
            }
        }

        // Reload on shared year-filter change, only once this panel has
        // actually been viewed at least once.
        document.getElementById('yearFilter')?.addEventListener('change', (e) => {
            if (pubLoaded) {
                loadPublications(e.target.value);
            }
        });

        // Redraw when the Publications tab is clicked — charts need a
        // visible container to size correctly, and this panel starts hidden.
        document.querySelectorAll('.ra-tab').forEach((tab) => {
            tab.addEventListener('click', () => {
                if (tab.dataset.tab === 'publications') {
                    const year = document.getElementById('yearFilter')?.value || '';
                    loadPublications(year);
                }
            });
        });

        // Load immediately if this panel is already active on first paint.
        document.addEventListener('DOMContentLoaded', () => {
            const panel = document.getElementById('publicationsDashboard');
            if (panel && panel.classList.contains('is-active')) {
                loadPublications('');
            }
        });
    })();
</script>