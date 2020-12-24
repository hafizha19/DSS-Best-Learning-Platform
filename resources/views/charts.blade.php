<script>

    // Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

var kriteria = document.getElementById('kriteriaChart').getContext('2d');
var alternatif = document.getElementById('alternatifChart').getContext('2d');

var myBarChart = new Chart(kriteria, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($kriteria['key']) ?>,
        // labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [{
            label: 'Nilai Eigen Vector',
            backgroundColor: "#4e73df",
            hoverBackgroundColor: "#2e59d9",
            borderColor: "#4e73df",
            barThickness: 2,
            maxBarThickness: 4,
            data: <?php echo json_encode($kriteria['eigen']) ?>
        }]
    },
    options: {
        scales: {
            xAxes: [{
                gridLines: {
                    offsetGridLines: true
                }
            }]
        },
        legend: {
            display: false
        }
    }
});

var myBarChart2 = new Chart(alternatif, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($alternatif['key']) ?>,
        // labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [{
            label: 'Nilai Eigen Vector',
            backgroundColor: "#7c98eb",
            hoverBackgroundColor: "#2e59d9",
            borderColor: "#4e73df",
            barThickness: 2,
            maxBarThickness: 4,
            data: <?php echo json_encode($alternatif['eigen']) ?>
        }]
    },
    options: {
        scales: {
            xAxes: [{
                gridLines: {
                    offsetGridLines: true
                }
            }]
        },
        legend: {
            display: false
        }
    }
});

</script>