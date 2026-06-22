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
                    <h2 class="ra-card-title">Research output lead indicators</h2>
                    <div class="ra-card-sub">summary from fact_research_output</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-sm-6 col-lg-3">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Total outputs</div>
            <div class="ra-kpi-value" id="overviewTotalOutputs">—</div>
            <div class="ra-kpi-foot">all research outputs</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Completed outputs</div>
            <div class="ra-kpi-value" id="overviewCompletedOutputs">—</div>
            <div class="ra-kpi-foot">based on complete count</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Output completion</div>
            <div class="ra-kpi-value" id="overviewOutputCompletionRate">—</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Active output campuses</div>
            <div class="ra-kpi-value" id="overviewOutputCampuses">—</div>
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
