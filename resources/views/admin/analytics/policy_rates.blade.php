@extends('admin.layouts.app')

@section('title', 'Agent Policy Rates')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Policy</a></li>
<li class="breadcrumb-item active" aria-current="page">Agent Policy </li>
@endsection

@section('content')
<div class="row layout-top-spacing">
    <div class="col-lg-6 mx-auto mt-4">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Combined Monthly Policy Count</h5>
                <canvas id="combinedChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-12 mx-auto mt-4">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Agent Policy </h5>
                <div class="table-responsive">
                    <table class="table table-dark table-borderless">
                        <tbody id="agentDataBody">
                            <!-- Data will be inserted here by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const policyRates = @json($policyRates);
        const sortedAgents = Object.entries(policyRates)
            .filter(([_, agentData]) => agentData.data && agentData.data.length > 0)
            .sort((a, b) => {
                return b[1].data.reduce((sum, val) => sum + (val || 0), 0) -
                       a[1].data.reduce((sum, val) => sum + (val || 0), 0);
            });

        const tableBody = document.getElementById('agentDataBody');

        // Function to convert month-year to abbreviated month name
        function getMonthAbbr(monthYear) {
            const [month, _] = monthYear.split('-');
            const date = new Date(2024, parseInt(month) - 1, 1);
            return date.toLocaleString('default', { month: 'short' });
        }

        // Use the labels from the first non-empty agent data
        const firstAgentWithData = sortedAgents.find(agent => agent[1].labels && agent[1].labels.length > 0);
        const monthLabels = firstAgentWithData ? firstAgentWithData[1].labels.map(getMonthAbbr) : [];

        // Calculate combined data
        const combinedData = new Array(monthLabels.length).fill(0);
        sortedAgents.forEach(([_, agentData]) => {
            agentData.data.forEach((value, index) => {
                combinedData[index] += value || 0;
            });
        });

        // Create combined chart
        const combinedCtx = document.getElementById('combinedChart').getContext('2d');
        new Chart(combinedCtx, {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Combined Policy Count',
                    data: combinedData,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        ticks: { color: '#ffffff' }
                    },
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        ticks: { color: '#ffffff' }
                    }
                },
                plugins: { legend: { display: false } }
            }
        });

        // Individual agent rows
        sortedAgents.forEach(([agentId, agentData]) => {
            const row = tableBody.insertRow();

            // Agent name and total cell
            const nameCell = row.insertCell();
            const total = agentData.data.reduce((sum, count) => sum + (count || 0), 0);
            const daysSinceLastPolicy = agentData.days_since_last_policy;
            nameCell.innerHTML = `
                <span style="color: #ffffff; font-weight: bold;">${agentData.agent_name}</span>
                <span style="font-size: 0.9em; color: #a0a0a0;"> (${total})</span><br>
                 <span style="font-size: 0.8em; color: #ff9800;">Last policy: ${daysSinceLastPolicy} days ago</span>
            `;
            nameCell.style.width = '15%';

            // Monthly data cell
            const monthlyDataCell = row.insertCell();
            monthlyDataCell.style.width = '55%';
            let monthlyHtml = '<div style="display: flex; justify-content: space-between;">';
            agentData.labels.forEach((label, index) => {
                const value = agentData.data[index] || 0;
                const monthAbbr = getMonthAbbr(label);
                monthlyHtml += `
                    <div style="text-align: center; padding: 0 5px;">
                        <div style="font-size: 0.8em; color: #a0a0a0;">${monthAbbr}</div>
                        <div style="background-color: #3a3a3a; padding: 8px; margin-top: 5px; border-radius: 5px; color: #ffffff;">${value}</div>
                    </div>
                `;
            });
            monthlyHtml += '</div>';
            monthlyDataCell.innerHTML = monthlyHtml;

            // Graph cell
            const graphCell = row.insertCell();
            graphCell.style.width = '30%';
            const canvas = document.createElement('canvas');
            canvas.height = 75;
            canvas.width = 300;
            graphCell.appendChild(canvas);

            const chartColor = getRandomColor();
            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: agentData.labels.map(getMonthAbbr),
                    datasets: [{
                        data: agentData.data.map(value => value || 0),
                        borderColor: chartColor,
                        backgroundColor: chartColor + '20',
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: chartColor,
                        borderWidth: 3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { display: false },
                        y: {
                            display: false,
                            beginAtZero: true,
                            suggestedMin: 0,
                            suggestedMax: Math.max(...agentData.data.map(value => value || 0)) + 1
                        }
                    },
                    elements: {
                        point: {
                            radius: 4,
                            hoverRadius: 6,
                            hoverBorderWidth: 2
                        }
                    }
                }
            });
        });
    });

    function getRandomColor() {
        const hue = Math.floor(Math.random() * 360);
        return `hsl(${hue}, 70%, 60%)`;
    }
</script>
@endsection
