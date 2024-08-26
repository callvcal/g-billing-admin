<div class="container-scroller">
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper" style="padding: 20px;">
                <div class="row">
                    @foreach ($data['counts'] as $item)
                        <div class="col-md-3 grid-margin stretch-card">
                            <div class="card mb-3">
                                <p class="card-header">{{ $item['name'] }}</p>
                                <div class="card-body" style="text-align: center;">
                                    <h5 class="card-title" class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">
                                        {{ $item['count'] }}</h5>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div>
                    <div class="card" style="width:400px; float: right;">
                        <div class="row p-1 m-1">
                            <label class="col" for="summary">Choose Duration</label>
                            <select class="col form-select" class="form-select" name="summary" id="summary">
                                <option id="day" value="day">
                                    Last 24 hours</option>
                                <option id="week" value="week">
                                    Last 7 days</option>
                                <option id="month" value="month">
                                    Last 30 days</option>

                                <option id="6month" value="6month">
                                    Last 6 months</option>



                                <option id="all" value="all">
                                    Overall</option>
                            </select>
                        </div>
                    </div>
                    <div class="container">
                        @include('reports', ['reports' => $data['reports']])
                    </div>

                    <canvas id="myChart" width="400" height="400" style="max-height:400px;"></canvas>
                    <h5>Expense & Earning Analysis</h5>
                    <canvas id="offlineTransactions" width="400" height="400" style="max-height:400px;"></canvas>
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

    console.log(offlineTrnData);


    var summary = document.getElementById('summary');
    summary.addEventListener('change', function(event) {
        console.log(event.target.value);
        var value = event.target.value;
        var route = "{{ url('admin/sells-summary') }}";

        fetch(route + '/' + value)
            .then(response => response.json())
            .then(data => {
                console.log('Sales data:', data);
                sellsData = data['sells'];
                offlineTrnData = data['trns'];
                showChart();
                showOfflineTransactions();
                // Handle the sales data
            })
            .catch(error => {
                console.log(error);
            });

    });



    // Make the API call


    var chart;
    var offlineTransactionsChart;
    showChart();
    showOfflineTransactions();

    function showChart() {
        if (chart) {
            chart.destroy();
        }

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
                animations: {
                    tension: {
                        duration: 1000,
                        easing: 'linear',
                        from: 1,
                        to: 0,
                        loop: true
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: 'rgb(75, 192, 192)'
                        }
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(0,0,0,0.7)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        time: {
                            unit: 'week',
                            tooltipFormat: 'll',
                            displayFormats: {
                                week: 'MMM DD'
                            }
                        },
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
        if (offlineTransactionsChart) {
            offlineTransactionsChart.destroy();
        }
        console.log('showOfflineTransactions');
        var labels = offlineTrnData.map(data => data.type);
        var data = offlineTrnData.map(data => data.amount);
        console.log('showOfflineTransactions');
        console.log(labels);
        console.log(data);

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
