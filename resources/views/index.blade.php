<div class="container-scroller">
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper" style="padding: 20px;">
                <div class="row">
                    @foreach ($data['counts'] as $item)
                        <div class="col-md-3 grid-margin stretch-card">
                            <div class="card mb-3 shadow-sm" style="border-radius: 10px; background-color: #f4f4f4; transition: transform 0.2s;">
                                <p class="card-header" style="background-color: #6a0dad; color: white; border-radius: 10px 10px 0 0; font-size: 1.2rem;">{{ $item['name'] }}</p>
                                <div class="card-body" style="text-align: center; padding: 20px;">
                                    <h5 class="card-title mb-0" style="font-size: 2rem; color: #333;">{{ $item['count'] }}</h5>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5">
                    <div class="container">
                        @include('reports', ['reports' => $data['reports']])
                    </div>

                    <h5 class="mt-4">Expense & Earning Analysis</h5>

                    <!-- Chart 1 -->
                    <canvas id="myChart" width="400" height="400" style="max-height: 400px;"></canvas>

                    <!-- Chart 2 -->
                    <h5 class="mt-4">Offline Transactions</h5>
                    <canvas id="offlineTransactions" width="400" height="400" style="max-height: 400px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const offlineTransactions = document.getElementById('offlineTransactions').getContext('2d');
    
    var sellsData = @json($data['statistics']['sells']);
    var offlineTrnData = @json($data['offlineTranctions']);

    var chart;
    var offlineTransactionsChart;

    showChart();
    showOfflineTransactions();

    function showChart() {
        if (chart) chart.destroy();

        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: sellsData.map(data => data.date),
                datasets: [{
                    label: 'Total Sales in Rs',
                    data: sellsData.map(data => data.total_sales),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    tension: 0.4
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#007bff'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.7)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#007bff',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        },
                        ticks: {
                            maxTicksLimit: 10,
                            autoSkip: true,
                            maxRotation: 0,
                            minRotation: 0
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Total Sales (Rs)'
                        }
                    }
                }
            }
        });
    }

    function showOfflineTransactions() {
        if (offlineTransactionsChart) offlineTransactionsChart.destroy();

        var labels = offlineTrnData.map(data => data.type);
        var data = offlineTrnData.map(data => data.amount);

        offlineTransactionsChart = new Chart(offlineTransactions, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Rs.',
                    data: data,
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                    hoverOffset: 4
                }]
            }
        });
    }
</script>
