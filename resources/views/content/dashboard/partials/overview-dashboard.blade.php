{{--
    Overview dashboard panel.
    This version compresses the executive summary into one four-card row so
    the landing tab reads like the user's reference dashboard.
--}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="ra-overview-card">
            <div class="ra-overview-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M7 3.5h7l3 3v14H7v-17Z" stroke-width="1.7" stroke-linejoin="round" />
                    <path d="M14 3.5v3h3M9.5 11h5M9.5 14.5h5" stroke-width="1.7" stroke-linecap="round" />
                </svg>
            </div>
            <div class="ra-overview-label">Total Proposals</div>
            <div class="ra-overview-value" id="overviewHeroProposals"><span class="ra-skel"></span></div>
            <div class="ra-overview-foot" id="overviewPeriodProposal">Across all years</div>
            <div class="ra-overview-watermark" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M7 3.5h7l3 3v14H7v-17Z" stroke-width="1.2" stroke-linejoin="round" />
                    <path d="M14 3.5v3h3M9.5 11h5M9.5 14.5h5" stroke-width="1.2" stroke-linecap="round" />
                </svg>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="ra-overview-card">
            <div class="ra-overview-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="8.5" stroke-width="1.7" />
                    <path d="m8.8 12.2 2.2 2.2 4.6-5.1" stroke-width="1.7" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </div>
            <div class="ra-overview-label">Total Completed Papers</div>
            <div class="ra-overview-value" id="overviewHeroCompletedPapers"><span class="ra-skel"></span></div>
            <div class="ra-overview-foot" id="overviewPeriodCompleted">Across all years</div>
            <div class="ra-overview-watermark" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="8.5" stroke-width="1.2" />
                    <path d="m8.8 12.2 2.2 2.2 4.6-5.1" stroke-width="1.2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="ra-overview-card">
            <div class="ra-overview-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                    <path
                        d="M4.5 6.5v11.2c2.2-.9 4.6-.7 7.5.8 2.9-1.5 5.3-1.7 7.5-.8V6.5c-2.2-.9-4.6-.7-7.5.8-2.9-1.5-5.3-1.7-7.5-.8Z"
                        stroke-width="1.6" stroke-linejoin="round" />
                    <path d="M12 7.3v11.2" stroke-width="1.6" stroke-linecap="round" />
                </svg>
            </div>
            <div class="ra-overview-label">Total Published Papers</div>
            <div class="ra-overview-value" id="overviewHeroPublishedPapers"><span class="ra-skel"></span></div>
            <div class="ra-overview-foot" id="overviewPeriodPublished">Across all years</div>
            <div class="ra-overview-watermark" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                    <path
                        d="M4.5 6.5v11.2c2.2-.9 4.6-.7 7.5.8 2.9-1.5 5.3-1.7 7.5-.8V6.5c-2.2-.9-4.6-.7-7.5.8-2.9-1.5-5.3-1.7-7.5-.8Z"
                        stroke-width="1.2" stroke-linejoin="round" />
                    <path d="M12 7.3v11.2" stroke-width="1.2" stroke-linecap="round" />
                </svg>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="ra-overview-card">
            <div class="ra-overview-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M12 3.5v17M7.5 7.5h6.25a3.25 3.25 0 0 1 0 6.5H9.5" stroke-width="1.7"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M6 20.5h12" stroke-width="1.7" stroke-linecap="round" />
                </svg>
            </div>
            <div class="ra-overview-label">Total Fund Allocation</div>
            <div class="ra-overview-value" id="overviewHeroFundAllocation"><span class="ra-skel"></span></div>
            <div class="ra-overview-foot" id="overviewPeriodFunding">Across all years</div>
            <div class="ra-overview-watermark" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                    <ellipse cx="12" cy="6.5" rx="5.5" ry="2.5" stroke-width="1.2" />
                    <path d="M6.5 6.5v4c0 1.4 2.5 2.5 5.5 2.5s5.5-1.1 5.5-2.5v-4" stroke-width="1.2" />
                    <path d="M6.5 10.5v4c0 1.4 2.5 2.5 5.5 2.5s5.5-1.1 5.5-2.5v-4" stroke-width="1.2" />
                    <path d="M6.5 14.5v3c0 1.4 2.5 2.5 5.5 2.5s5.5-1.1 5.5-2.5v-3" stroke-width="1.2" />
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12">
        <div class="ra-card ra-overview-trend-card">
            <div class="ra-card-head ra-overview-trend-head">
                <div>
                    <h2 class="ra-card-title" id="overviewTrendTitle">Trends Over Time</h2>
                    <div class="ra-card-sub">proposal volume, completed papers, and published papers by year</div>
                </div>
                <div class="ra-filter">
                    {{-- This local filter controls only the overview trend card so
                         users can narrow the multi-year chart without changing
                         the top summary cards or the other dashboards. --}}
                    <label class="ra-filter-label" for="overviewTrendYearFilter">Trend range</label>
                    <select id="overviewTrendYearFilter" class="ra-select">
                        <option value="">All years</option>
                    </select>
                </div>
            </div>
            <div class="px-3 pb-3">
                <div id="overviewTrendChart" style="min-height:360px;"></div>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Overview panel lead indicators.
     * Pull one summary card from each source area so the landing tab mirrors
     * the proposal, publication, and funding dashboards without duplicating
     * their deeper chart sections.
     */
    (function () {
        const API_BASE = window.RESEARCH_API_BASE ||
            '{{ rtrim(config('services.research_api.url', 'http://127.0.0.1:8001'), '/') }}';

        let overviewLoaded = false;
        let overviewTrendLoaded = false;
        let overviewTrendChart = null;
        let overviewTrendRows = [];

        const fmtInt = (n) => Number(n ?? 0).toLocaleString('en-US');
        const fmtCurrency = (n) => `₱${Number(n ?? 0).toLocaleString('en-US', { maximumFractionDigits: 0 })}`;

        function setText(id, value) {
            const el = document.getElementById(id);
            if (el) el.textContent = value;
        }

        function applyOverviewPeriodLabel(year) {
            // Keep a single period phrase across all cards so the executive
            // summary clearly matches the active year filter.
            const label = year ? `For ${year}` : 'Across all years';
            [
                'overviewPeriodProposal',
                'overviewPeriodCompleted',
                'overviewPeriodPublished',
                'overviewPeriodFunding',
            ].forEach((id) => setText(id, label));
        }

        function buildProposalsUrl(year) {
            const url = new URL(`${API_BASE}/api/proposals/dashboard`);
            if (year) url.searchParams.set('year', year);
            return url.toString();
        }

        function buildPublicationsUrl(year) {
            const url = new URL(`${API_BASE}/api/publications/dashboard`);
            if (year) url.searchParams.set('year', year);
            return url.toString();
        }

        function buildFundingUrl(year) {
            const url = new URL(`${API_BASE}/api/funding/dashboard`);
            if (year) url.searchParams.set('year', year);
            return url.toString();
        }

        function destroyOverviewTrendChart() {
            if (overviewTrendChart) {
                overviewTrendChart.destroy();
                overviewTrendChart = null;
            }
        }

        function setOverviewTrendOptions(years) {
            const select = document.getElementById('overviewTrendYearFilter');
            if (!select) return;

            const currentValue = select.value;
            select.innerHTML = '<option value="">All years</option>';

            years.forEach((year) => {
                const option = document.createElement('option');
                option.value = String(year);
                option.textContent = String(year);
                select.appendChild(option);
            });

            select.value = years.includes(Number(currentValue)) ? currentValue : '';
        }

        function syncOverviewTrendTitle(rows, selectedYear = '') {
            const titleEl = document.getElementById('overviewTrendTitle');
            if (!titleEl) return;

            if (selectedYear) {
                titleEl.textContent = `Trends Over Time (${selectedYear})`;
                return;
            }

            const years = rows.map((row) => Number(row.year)).filter(Boolean);
            if (!years.length) {
                titleEl.textContent = 'Trends Over Time';
                return;
            }

            titleEl.textContent = `Trends Over Time (${Math.min(...years)} - ${Math.max(...years)})`;
        }

        function renderOverviewTrend(rows, selectedYear = '') {
            const chartEl = document.getElementById('overviewTrendChart');
            if (!chartEl) return;

            const filteredRows = selectedYear
                ? rows.filter((row) => String(row.year) === String(selectedYear))
                : rows;

            syncOverviewTrendTitle(filteredRows.length ? filteredRows : rows, selectedYear);
            destroyOverviewTrendChart();

            if (!filteredRows.length) {
                chartEl.innerHTML = '<div class="ra-empty">no trend data yet</div>';
                return;
            }

            chartEl.innerHTML = '';

            overviewTrendChart = new ApexCharts(chartEl, {
                chart: {
                    type: 'line',
                    height: 360,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    }
                },
                series: [{
                    name: 'Proposals',
                    data: filteredRows.map((row) => row.proposals),
                }, {
                    name: 'Completed Papers',
                    data: filteredRows.map((row) => row.completed_papers),
                }, {
                    name: 'Published Papers',
                    data: filteredRows.map((row) => row.published_papers),
                }],
                colors: ['#163FD6', '#2D6AF6', '#8AB5FF'],
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                markers: {
                    size: 6,
                    strokeWidth: 0,
                    hover: {
                        size: 7
                    }
                },
                dataLabels: {
                    enabled: true,
                    offsetY: -10,
                    style: {
                        fontSize: '12px',
                        fontWeight: '700',
                        colors: ['#1E4ED8']
                    },
                    background: {
                        enabled: false
                    },
                    formatter: (value) => fmtInt(value),
                },
                grid: {
                    borderColor: '#E5EDFF',
                    strokeDashArray: 4,
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left',
                    fontSize: '13px',
                    labels: {
                        colors: '#3552A3'
                    }
                },
                xaxis: {
                    categories: filteredRows.map((row) => String(row.year)),
                    labels: {
                        style: {
                            colors: '#3552A3',
                            fontSize: '12px'
                        }
                    },
                    axisBorder: {
                        color: '#D9E2FF'
                    },
                    axisTicks: {
                        color: '#D9E2FF'
                    }
                },
                yaxis: {
                    min: 0,
                    forceNiceScale: true,
                    labels: {
                        formatter: (value) => fmtInt(value),
                        style: {
                            colors: '#3552A3',
                            fontSize: '12px'
                        }
                    }
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: (value) => fmtInt(value),
                    }
                }
            });

            overviewTrendChart.render();
        }

        function mergeOverviewTrendData(proposals, publications) {
            const proposalMap = new Map(
                (proposals.proposals_by_year || []).map((row) => [Number(row.year), Number(row.total_proposals || 0)])
            );
            const publicationMap = new Map(
                (publications.yearly_trend || []).map((row) => [
                    Number(row.yr),
                    {
                        published_papers: Number(row.total_outputs || 0),
                        completed_papers: Number(row.completed_outputs || 0),
                    }
                ])
            );

            const yearSet = new Set([
                ...proposalMap.keys(),
                ...publicationMap.keys(),
            ]);

            return Array.from(yearSet)
                .filter(Boolean)
                .sort((a, b) => a - b)
                .map((year) => {
                    const publicationRow = publicationMap.get(year) || {
                        published_papers: 0,
                        completed_papers: 0,
                    };

                    return {
                        year,
                        proposals: proposalMap.get(year) || 0,
                        completed_papers: publicationRow.completed_papers,
                        published_papers: publicationRow.published_papers,
                    };
                });
        }

        async function loadOverviewTrend() {
            try {
                const [proposalRes, publicationRes] = await Promise.all([
                    fetch(buildProposalsUrl(''), { headers: { Accept: 'application/json' } }),
                    fetch(buildPublicationsUrl(''), { headers: { Accept: 'application/json' } }),
                ]);

                if (!proposalRes.ok) throw new Error(`Proposal trend service responded with ${proposalRes.status}`);
                if (!publicationRes.ok) throw new Error(`Publication trend service responded with ${publicationRes.status}`);

                const proposals = await proposalRes.json();
                const publications = await publicationRes.json();

                overviewTrendRows = mergeOverviewTrendData(proposals, publications);
                setOverviewTrendOptions(overviewTrendRows.map((row) => row.year));
                renderOverviewTrend(overviewTrendRows, document.getElementById('overviewTrendYearFilter')?.value || '');
                overviewTrendLoaded = true;
            } catch (err) {
                console.error(err);
                destroyOverviewTrendChart();
                const chartEl = document.getElementById('overviewTrendChart');
                if (chartEl) {
                    chartEl.innerHTML = '<div class="ra-empty">could not load overview trend data</div>';
                }
            }
        }

        async function loadOverview(year = '') {
            try {
                const [proposalRes, pubRes, fundRes] = await Promise.all([
                    fetch(buildProposalsUrl(year), { headers: { Accept: 'application/json' } }),
                    fetch(buildPublicationsUrl(year), { headers: { Accept: 'application/json' } }),
                    fetch(buildFundingUrl(year), { headers: { Accept: 'application/json' } }),
                ]);
                if (!proposalRes.ok) throw new Error(`Proposal service responded with ${proposalRes.status}`);
                if (!pubRes.ok) throw new Error(`Publications service responded with ${pubRes.status}`);
                if (!fundRes.ok) throw new Error(`Funding service responded with ${fundRes.status}`);

                const proposals = await proposalRes.json();
                const publications = await pubRes.json();
                const funding = await fundRes.json();

                const totalProposals = (proposals.status_distribution || [])
                    .reduce((sum, row) => sum + Number(row.total_proposals || 0), 0);

                setText('overviewHeroProposals', fmtInt(totalProposals));
                setText('overviewHeroCompletedPapers', fmtInt(publications.completed_outputs?.[0]?.completed_outputs));
                setText('overviewHeroPublishedPapers', fmtInt(publications.total_outputs?.[0]?.total_outputs));
                setText('overviewHeroFundAllocation', fmtCurrency(funding.total_allocated_fund?.[0]?.total_allocated_fund));
                applyOverviewPeriodLabel(year);

                overviewLoaded = true;

                if (!overviewTrendLoaded) {
                    loadOverviewTrend();
                }
            } catch (err) {
                console.error(err);
                [
                    'overviewHeroProposals',
                    'overviewHeroCompletedPapers',
                    'overviewHeroPublishedPapers',
                    'overviewHeroFundAllocation',
                ].forEach((id) => setText(id, '—'));
                applyOverviewPeriodLabel(year);
            }
        }

        // Reload when the shared year filter changes, but only once this
        // panel has actually loaded the data at least once.
        document.getElementById('yearFilter')?.addEventListener('change', (e) => {
            if (overviewLoaded) {
                loadOverview(e.target.value);
            }
        });

        // Reload when the Overview tab is clicked, in case the cards were
        // stale from a year filter changed elsewhere while hidden.
        document.querySelectorAll('.ra-tab').forEach((tab) => {
            tab.addEventListener('click', () => {
                if (tab.dataset.tab === 'overview') {
                    const year = document.getElementById('yearFilter')?.value || '';
                    loadOverview(year);
                    if (overviewTrendLoaded) {
                        renderOverviewTrend(
                            overviewTrendRows,
                            document.getElementById('overviewTrendYearFilter')?.value || ''
                        );
                    }
                }
            });
        });

        document.getElementById('overviewTrendYearFilter')?.addEventListener('change', (e) => {
            renderOverviewTrend(overviewTrendRows, e.target.value);
        });

        // Overview is the default active panel on first paint, so load
        // immediately rather than waiting for a tab click.
        document.addEventListener('DOMContentLoaded', () => {
            const panel = document.getElementById('overviewDashboard');
            if (panel && panel.classList.contains('is-active')) {
                loadOverview('');
            }
        });
    })();
</script>
