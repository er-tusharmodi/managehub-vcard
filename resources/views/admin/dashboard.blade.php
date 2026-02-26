@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Dashboard</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="row g-3">
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="fs-14 mb-1">Website Traffic</div>
                            </div>

                            <div class="d-flex align-items-baseline mb-2">
                                <div class="fs-22 mb-0 me-2 fw-semibold text-black">{{ number_format($totalVisits) }}</div>
                                <div class="me-auto">
                                    <span class="text-primary d-inline-flex align-items-center">
                                        15%
                                        <i data-feather="trending-up" class="ms-1" style="height: 22px; width: 22px;"></i>
                                    </span>
                                </div>
                            </div>
                            <div id="website-visitors" class="apex-charts"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="fs-14 mb-1">Conversion rate</div>
                            </div>

                            <div class="d-flex align-items-baseline mb-2">
                                <div class="fs-22 mb-0 me-2 fw-semibold text-black">{{ number_format($conversionRate, 2) }}%</div>
                                <div class="me-auto">
                                    <span class="text-danger d-inline-flex align-items-center">
                                        10%
                                        <i data-feather="trending-down" class="ms-1" style="height: 22px; width: 22px;"></i>
                                    </span>
                                </div>
                            </div>
                            <div id="conversion-visitors" class="apex-charts"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="fs-14 mb-1">Avg Daily Visits</div>
                            </div>

                            <div class="d-flex align-items-baseline mb-2">
                                <div class="fs-22 mb-0 me-2 fw-semibold text-black">{{ number_format($avgDailyVisits) }}</div>
                                <div class="me-auto">
                                    <span class="text-success d-inline-flex align-items-center">
                                        25%
                                        <i data-feather="trending-up" class="ms-1" style="height: 22px; width: 22px;"></i>
                                    </span>
                                </div>
                            </div>
                            <div id="session-visitors" class="apex-charts"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="fs-14 mb-1">Active Users</div>
                            </div>

                            <div class="d-flex align-items-baseline mb-2">
                                <div class="fs-22 mb-0 me-2 fw-semibold text-black">{{ number_format($totalUsers) }}</div>
                                <div class="me-auto">
                                    <span class="text-success d-inline-flex align-items-center">
                                        4%
                                        <i data-feather="trending-up" class="ms-1" style="height: 22px; width: 22px;"></i>
                                    </span>
                                </div>
                            </div>
                            <div id="active-users" class="apex-charts"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="border border-dark rounded-2 me-2 widget-icons-sections">
                            <i data-feather="smartphone" class="widgets-icons"></i>
                        </div>
                        <h5 class="card-title mb-0">Device Breakdown</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div id="device-breakdown" class="apex-charts"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="border border-dark rounded-2 me-2 widget-icons-sections">
                            <i data-feather="globe" class="widgets-icons"></i>
                        </div>
                        <h5 class="card-title mb-0">Browser Breakdown</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div id="browser-breakdown" class="apex-charts"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xl-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="border border-dark rounded-2 me-2 widget-icons-sections">
                            <i data-feather="bar-chart" class="widgets-icons"></i>
                        </div>
                        <h5 class="card-title mb-0">Monthly Sales</h5>
                    </div>
                </div>

                <div class="card-body">
                    <div id="monthly-sales" class="apex-charts"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card overflow-hidden">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="border border-dark rounded-2 me-2 widget-icons-sections">
                            <i data-feather="tablet" class="widgets-icons"></i>
                        </div>
                        <h5 class="card-title mb-0">Best Traffic Source</h5>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-traffic mb-0">
                            <tbody>
                                <thead>
                                    <tr>
                                        <th>Network</th>
                                        <th colspan="2">Visitors</th>
                                    </tr>
                                </thead>

                                @php
                                    $trafficTotal = collect($trafficSources)->sum('visits');
                                    $trafficColors = ['bg-primary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-secondary', 'bg-dark'];
                                @endphp

                                @forelse ($trafficSources as $index => $source)
                                    @php
                                        $percent = $trafficTotal > 0 ? round(($source['visits'] / $trafficTotal) * 100, 2) : 0;
                                        $color = $trafficColors[$index % count($trafficColors)];
                                    @endphp
                                    <tr>
                                        <td>{{ $source['source'] }}</td>
                                        <td>{{ number_format($source['visits']) }}</td>
                                        <td class="w-50">
                                            <div class="progress progress-md mt-0">
                                                <div class="progress-bar {{ $color }}" style="width: {{ $percent }}%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No traffic data yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="border border-dark rounded-2 me-2 widget-icons-sections">
                            <i data-feather="minus-square" class="widgets-icons"></i>
                        </div>
                        <h5 class="card-title mb-0">Audiences By Time Of Day</h5>
                    </div>
                </div>

                <div class="card-body">
                    <div id="audiences-daily" class="apex-charts mt-n3"></div>
                </div>

            </div>
        </div>

        <div class="col-md-6 col-xl-6">
            <div class="card overflow-hidden">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="border border-dark rounded-2 me-2 widget-icons-sections">
                            <i data-feather="table" class="widgets-icons"></i>
                        </div>
                        <h5 class="card-title mb-0">Most Visited Pages</h5>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-traffic mb-0">
                            <tbody>
                                <thead>
                                    <tr>
                                        <th>Page name</th>
                                        <th>Visitors</th>
                                        <th colspan="2">Share</th>
                                    </tr>
                                </thead>

                                @php
                                    $pageTotal = collect($topPages)->sum('visits');
                                @endphp

                                @forelse ($topPages as $page)
                                    @php
                                        $percent = $pageTotal > 0 ? round(($page['visits'] / $pageTotal) * 100, 2) : 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $page['page'] }}
                                        </td>
                                        <td>{{ number_format($page['visits']) }}</td>
                                        <td>{{ $percent }}%</td>
                                        <td class="w-25">
                                            <div class="progress progress-md mt-0">
                                                <div class="progress-bar bg-primary" style="width: {{ $percent }}%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No page data yet.</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('backendtheme/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script>
        const last30Visits = @json($last30Visits);
        const last30Labels = @json($last30Days);
        const monthlySales = @json($monthlySales);
        const monthLabels = @json($monthLabels);
        const deviceBreakdown = @json($deviceBreakdown);
        const browserBreakdown = @json($browserBreakdown);

        const buildSparkline = (selector, seriesData, color) => {
            const el = document.querySelector(selector);
            if (!el) {
                return;
            }
            const options = {
                chart: {
                    type: 'area',
                    height: 45,
                    sparkline: { enabled: true },
                    animations: { enabled: false }
                },
                dataLabels: { enabled: false },
                fill: { opacity: 0.16, type: 'solid' },
                stroke: { width: 2, lineCap: 'round', curve: 'smooth' },
                series: [{ name: 'Visits', data: seriesData }],
                colors: [color],
                tooltip: { theme: 'light' }
            };
            const chart = new ApexCharts(el, options);
            chart.render();
        };

        buildSparkline('#website-visitors', last30Visits, '#537AEF');
        buildSparkline('#conversion-visitors', last30Visits.map(value => Math.round(value * 0.1)), '#ec8290');
        buildSparkline('#session-visitors', last30Visits.map(value => Math.max(1, Math.round(value * 0.3))), '#28a745');
        buildSparkline('#active-users', last30Visits.map(value => Math.max(1, Math.round(value * 0.2))), '#6f42c1');

        const monthlySalesEl = document.querySelector('#monthly-sales');
        if (monthlySalesEl) {
            const options = {
                chart: { type: 'bar', height: 307, parentHeightOffset: 0, toolbar: { show: false } },
                colors: ['#537AEF'],
                series: [{ name: 'Sales', data: monthlySales }],
                fill: { opacity: 1 },
                plotOptions: {
                    bar: {
                        columnWidth: '50%',
                        borderRadius: 4,
                        borderRadiusApplication: 'end',
                        borderRadiusWhenStacked: 'last'
                    }
                },
                grid: { strokeDashArray: 4, padding: { top: -20, right: 0, bottom: -4 }, xaxis: { lines: { show: true } } },
                xaxis: { categories: monthLabels, axisTicks: { color: '#f0f4f7' } },
                yaxis: { title: { text: 'Sales', style: { fontSize: '12px', fontWeight: 600 } } },
                tooltip: { theme: 'light' },
                legend: { position: 'top', show: true, horizontalAlign: 'center' },
                dataLabels: { enabled: false }
            };
            const chart = new ApexCharts(monthlySalesEl, options);
            chart.render();
        }

        const deviceEl = document.querySelector('#device-breakdown');
        if (deviceEl) {
            const labels = deviceBreakdown.map(item => item.label || 'Unknown');
            const series = deviceBreakdown.map(item => item.visits || 0);
            const options = {
                chart: { type: 'donut', height: 300 },
                labels,
                series,
                legend: { position: 'bottom' },
                colors: ['#537AEF', '#2ABE4E', '#F77824', '#6f42c1', '#adb5bd']
            };
            const chart = new ApexCharts(deviceEl, options);
            chart.render();
        }

        const browserEl = document.querySelector('#browser-breakdown');
        if (browserEl) {
            const labels = browserBreakdown.map(item => item.label || 'Unknown');
            const series = browserBreakdown.map(item => item.visits || 0);
            const options = {
                chart: { type: 'bar', height: 300, toolbar: { show: false } },
                series: [{ name: 'Visits', data: series }],
                xaxis: { categories: labels },
                colors: ['#537AEF'],
                plotOptions: { bar: { columnWidth: '45%', borderRadius: 4 } },
                dataLabels: { enabled: false }
            };
            const chart = new ApexCharts(browserEl, options);
            chart.render();
        }
    </script>
@endpush
