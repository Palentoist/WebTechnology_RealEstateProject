@extends('layouts.app', ['title' => 'Projects'])

@section('content')
    <div class="card">
        <h2>Projects</h2>
        <a href="{{ route('projects.create') }}" class="btn btn-primary">New Project</a>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Location</th>
                <th>Total Units</th>
                <th>Admin</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($projects as $project)
                <tr>
                    <td>{{ $project->id }}</td>
                    <td>{{ $project->name }}</td>
                    <td>{{ $project->location }}</td>
                    <td>{{ $project->total_units }}</td>
                    <td>{{ optional($project->admin)->name }}</td>
                    <td><a href="{{ route('projects.show', $project) }}" class="btn">View</a></td>
                </tr>
            @empty
                <tr><td colspan="6">No projects found.</td></tr>
            @endforelse
            </tbody>
        </table>
        {{ $projects->links() }}
    </div>

    @php
        // Get completed projects (projects with all units sold)
        $completedProjects = \App\Models\Project::with(['admin', 'units'])
            ->get()
            ->filter(function($project) {
                $totalUnits = $project->units->count();
                if ($totalUnits === 0) return false;
                $soldUnits = $project->units->where('status', 'sold')->count();
                return $totalUnits === $soldUnits;
            });

        // Prepare data for charts
        $completedProjectNames = $completedProjects->pluck('name')->values();
        $completedProjectUnits = $completedProjects->map(fn($p) => $p->units->count())->values();
        $completedProjectRevenue = $completedProjects->map(function($p) {
            return $p->units->sum('price');
        })->values();
    @endphp

    <!-- Completed Projects Section -->
    <div class="card" style="margin-top: 24px;">
        <h2>Completed Projects History</h2>
        <div style="color: var(--text-muted); font-size: 14px; margin-bottom: 16px;">
            Projects with all units sold
        </div>
        
        @if($completedProjects->count() > 0)
            <!-- Completed Projects Table -->
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Total Units Sold</th>
                    <th>Total Revenue</th>
                    <th>Admin</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($completedProjects as $project)
                    <tr>
                        <td>{{ $project->id }}</td>
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->location }}</td>
                        <td>{{ $project->units->count() }}</td>
                        <td>PKR {{ number_format($project->units->sum('price'), 2) }}</td>
                        <td>{{ optional($project->admin)->name }}</td>
                        <td>
                            <span style="display: inline-flex; align-items: center; gap: 6px; color: #22c55e; font-weight: 500;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                                Completed
                            </span>
                        </td>
                        <td><a href="{{ route('projects.show', $project) }}" class="btn">View</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Analytics Graphs Section -->
            <div style="margin-top: 32px;">
                <h3 style="font-size: 18px; margin-bottom: 20px; color: var(--text-primary);">
                    📊 Completed Projects Analytics
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 24px;">
                    <!-- Units Sold Chart -->
                    <div style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(148, 163, 184, 0.1); border-radius: 12px; padding: 20px;">
                        <div style="font-size: 14px; font-weight: 500; color: var(--text-primary); margin-bottom: 16px;">
                            Units Sold per Project
                        </div>
                        <canvas id="completedUnitsChart" style="max-height: 280px;"></canvas>
                    </div>
                    
                    <!-- Revenue Chart -->
                    <div style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(148, 163, 184, 0.1); border-radius: 12px; padding: 20px;">
                        <div style="font-size: 14px; font-weight: 500; color: var(--text-primary); margin-bottom: 16px;">
                            Revenue per Project
                        </div>
                        <canvas id="completedRevenueChart" style="max-height: 280px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Inline Chart.js Script -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const completedProjectNames = {!! json_encode($completedProjectNames) !!};
                    const completedProjectUnits = {!! json_encode($completedProjectUnits) !!};
                    const completedProjectRevenue = {!! json_encode($completedProjectRevenue) !!};

                    console.log('Chart data:', { completedProjectNames, completedProjectUnits, completedProjectRevenue });

                    if (completedProjectNames.length === 0) {
                        console.log('No completed projects to chart');
                        return;
                    }

                    const chartColors = {
                        cyan: '#22d3ee',
                        cyanSoft: 'rgba(34, 211, 238, 0.3)',
                        green: '#22c55e',
                        greenSoft: 'rgba(34, 197, 94, 0.3)',
                        grid: 'rgba(148, 163, 184, 0.15)',
                        text: '#e5e7eb',
                        muted: '#9ca3af',
                    };

                    const chartOptions = {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                borderColor: 'rgba(148, 163, 184, 0.3)',
                                borderWidth: 1,
                                padding: 12,
                                titleColor: chartColors.text,
                                bodyColor: chartColors.text,
                                cornerRadius: 8,
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { 
                                    color: chartColors.muted, 
                                    font: { size: 11 },
                                    maxRotation: 45,
                                    minRotation: 0
                                }
                            },
                            y: {
                                grid: { color: chartColors.grid },
                                ticks: { 
                                    color: chartColors.muted, 
                                    font: { size: 11 }
                                },
                                beginAtZero: true
                            }
                        }
                    };

                    // Units Sold Chart
                    const unitsChartEl = document.getElementById('completedUnitsChart');
                    if (unitsChartEl) {
                        console.log('Creating units chart');
                        new Chart(unitsChartEl, {
                            type: 'bar',
                            data: {
                                labels: completedProjectNames,
                                datasets: [{
                                    label: 'Units Sold',
                                    data: completedProjectUnits,
                                    backgroundColor: chartColors.cyanSoft,
                                    borderColor: chartColors.cyan,
                                    borderWidth: 2,
                                    borderRadius: 8,
                                }]
                            },
                            options: {
                                ...chartOptions,
                                scales: {
                                    ...chartOptions.scales,
                                    y: {
                                        ...chartOptions.scales.y,
                                        ticks: {
                                            ...chartOptions.scales.y.ticks,
                                            precision: 0,
                                            callback: function(value) {
                                                return value + ' units';
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // Revenue Chart
                    const revenueChartEl = document.getElementById('completedRevenueChart');
                    if (revenueChartEl) {
                        console.log('Creating revenue chart');
                        new Chart(revenueChartEl, {
                            type: 'bar',
                            data: {
                                labels: completedProjectNames,
                                datasets: [{
                                    label: 'Total Revenue',
                                    data: completedProjectRevenue,
                                    backgroundColor: chartColors.greenSoft,
                                    borderColor: chartColors.green,
                                    borderWidth: 2,
                                    borderRadius: 8,
                                }]
                            },
                            options: {
                                ...chartOptions,
                                plugins: {
                                    ...chartOptions.plugins,
                                    tooltip: {
                                        ...chartOptions.plugins.tooltip,
                                        callbacks: {
                                            label: function(context) {
                                                return 'Revenue: PKR ' + context.parsed.y.toLocaleString();
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    ...chartOptions.scales,
                                    y: {
                                        ...chartOptions.scales.y,
                                        ticks: {
                                            ...chartOptions.scales.y.ticks,
                                            callback: function(value) {
                                                return 'PKR ' + value.toLocaleString();
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                });
            </script>
        @else
            <div style="padding: 32px; text-align: center; color: var(--text-muted); background: rgba(255, 255, 255, 0.02); border-radius: 8px; border: 1px dashed rgba(148, 163, 184, 0.2);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 12px; opacity: 0.5;">
                    <path d="M9 11l3 3L22 4"></path>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                </svg>
                <p style="margin: 0; font-size: 15px;">No completed projects yet</p>
                <p style="margin: 8px 0 0; font-size: 13px; opacity: 0.7;">Projects will appear here once all units are sold</p>
            </div>
        @endif
    </div>
@endsection