<style>

.card {
    padding: 10px;

}
.card-in-chart{
    background: #ffffff;
    border-radius: 5px;
    box-shadow: rgba(0, 0, 0, 0.35) 5px 5px 15px;
    padding: 5px;
    border-radius: 20px;
}

</style>

<div class="container-fluid">
    <div class="row">
        <div class="card col-md-6">
            <div class="card-in-chart">
                <canvas id="barChart1"></canvas>
                <div class="text-center">
                    <button onclick="displayPrevious1()" class="btn btn-info"> <i class="fa-solid fa-chevron-left"></i></button>
                    <button onclick="displayNext1()"  class="btn btn-info"> <i class="fa-solid fa-chevron-right"></i> </button>
                </div>
            </div>
        </div>
        <div class="card col-md-6">
            <div class="card-in-chart">
                <canvas id="barChart2"></canvas>
                <div class="text-center">
                    <button onclick="displayPrevious2()" class="btn btn-danger"> <i class="fa-solid fa-chevron-left"></i> </button>
                    <button onclick="displayNext2()"  class="btn btn-danger"> <i class="fa-solid fa-chevron-right"></i> </button>
                </div>
            </div>
        </div>
        <div class="card col-md-4">
            <div class="card-in-chart">
                <canvas id="pieChart1"></canvas>
            </div>
        </div>
        <div class="card col-md-8">
            <div class="card-in-chart">
                <canvas id="barChart3"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    //BarChart1
    var dates = {!! $dates !!};
    var amounts1 = {!! $totals !!};

    var startIdx = dates.length - 7; // Start index for the initial displayed data
    var endIdx = dates.length - 1; // End index for the initial displayed data

    var displayedDates = dates.slice(startIdx, endIdx + 1);
    var displayedData1 = amounts1.slice(startIdx, endIdx + 1);


    var ctx1 = document.getElementById('barChart1').getContext('2d');
    var barChart1 = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: displayedDates,
            datasets: [{
                label: 'Daily Total',
                data: displayedData1,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
            }],
        },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                    },
                    y: {
                        beginAtZero: true,
                    },
                },
                legend: {
                    display: false,
                },
                tooltips: {
                    callbacks: {
                        label: function (context) {
                            var label = context.label || '';
                            var value = context.parsed.y || 0;
                            return label + ': ' + value;
                        }
                    }
                }
            },
        });

        function displayPrevious1() {
            startIdx -= 7;
            endIdx -= 7;
            updateChart1();
        }

        function displayNext1() {
            startIdx += 7;
            endIdx += 7;
            updateChart1();
        }

        function displayPrevious2() {
            startIdx -= 7;
            endIdx -= 7;
            updateChart2();
        }

        function displayNext2() {
            startIdx += 7;
            endIdx += 7;
            updateChart2();
        }

        function updateChart1() {
            var newDates = dates.slice(startIdx, endIdx + 1);
            var newData1 = amounts1.slice(startIdx, endIdx + 1);

            barChart1.data.labels = newDates;
            barChart1.data.datasets[0].data = newData1;
            barChart1.update();
        }
        function updateChart2() {
            var newDates = dates.slice(startIdx, endIdx + 1);
            var newData2 = amounts2.slice(startIdx, endIdx + 1);

            barChart2.data.labels = newDates;
            barChart2.data.datasets[0].data = newData2;
            barChart2.update();
        }

    //Barchart2
    // Fetch data for the second bar chart

    var amounts2 = {!! $amounts !!};
    var displayedData2 = amounts2.slice(startIdx, endIdx + 1);


    // Create another bar chart using Chart.js
    var ctx2 = document.getElementById('barChart2').getContext('2d');
    var barChart2 = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: displayedDates,
            datasets: [{
                label: 'Daily Member Amount',
                data: displayedData2,
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                x: {
                    grid: {
                        display: false,
                    },
                },
                y: {
                    beginAtZero: true,
                },
            },
            legend: {
                display: false,
            },
            tooltips: {
                callbacks: {
                    label: function (context) {
                        var label = context.label || '';
                        var value = context.parsed.y || 0;
                        return label + ': ' + value;
                    }
                }
            }
        },
    });

    //Piechart1
    // Fetch item names and quantities from the inventories data
    var inventories = {!! $inventories !!};


    // Extract labels (item names) and data (quantities) from the inventories array
    var labels = inventories.map(item => item.item_name);
    var data = inventories.map(item => item.qty);

    // Create a new pie chart using Chart.js
    var ctx3 = document.getElementById('pieChart1').getContext('2d');
    var pieChart = new Chart(ctx3, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    "#ea5545",
                    "#f46a9b",
                    "#ef9b20",
                    "#edbf33",
                    "#ede15b",
                    "#bdcf32",
                    "#87bc45",
                    "#27aeef",
                    "#b33dc6",
                    "#f90ab4"],
            }],
        },
        options: {
            responsive: true,
            layout: {
                padding: {
                    top: 30,
                    bottom: 30,
                },
            },
            maintainAspectRatio: true,
            legend: {
                position: 'bottom',
            },
            tooltips: {
                callbacks: {
                    label: function (tooltipItem, data) {
                        var label = data.labels[tooltipItem.index] || '';
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || '';
                        return label + ': ' + value;
                    }
                }
            }
        },
    });




    //barchart3
    // Fetch data from the database
    var invAndRec =  @json($inventoriesAll);
    var records = @json($records);

    var displayedData3 = records;
    var displayedData4 = invAndRec;
    var newdisplayData3 = {};

    console.log(invAndRec);
    console.log(records);
    invAndRec.forEach(function (item) {
        var count = 0;
        displayedData3.forEach(function (records) {
            if (records !== null) {
                count += (records.split(item).length - 1);
            }
        });
        newdisplayData3[item] = count;
    });

    console.log(Object.values(newdisplayData3));
    console.log(Object.keys(newdisplayData3));
    console.log(newdisplayData3);

    var valueDisplay3 = Object.values(newdisplayData3);

    // Create a bar chart
    var ctx4 = document.getElementById('barChart3').getContext('2d');
    var barChart3 = new Chart(ctx4, {
        type: 'bar',
        data: {
            labels: displayedData4,
            datasets: [{
                label: 'Order Count',
                data: newdisplayData3,
                backgroundColor:'#87bc45',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                x: {
                    grid: {
                        display: false,
                    },
                },
                y: {
                    beginAtZero: true,
                },
            },
            legend: {
                display: false,
            },
            tooltips: {
                callbacks: {
                    label: function (context) {
                        var label = context.label || '';
                        var value = context.parsed.y || 0;
                        return label + ': ' + value;
                    }
                }
            }
        },
    });



</script>
