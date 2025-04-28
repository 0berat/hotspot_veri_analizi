<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Raporlar - Grafikler</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<h2>Attacks</h2>
<canvas id="attacksChart" width="400" height="200"></canvas>

<h2>Gateway Alarms</h2>
<canvas id="gatewayAlarmsChart" width="400" height="200"></canvas>

<h2>Successful Logins</h2>
<canvas id="successfulLoginsChart" width="400" height="200"></canvas>

<script>
// 7 gün filtre ve gruplama fonksiyonu
function groupByExactLast7Days(items) {
    const now = new Date();
    const dates = [];

    // Son 7 günü oluştur
    for (let i = 6; i >= 0; i--) {
        const d = new Date(now);
        d.setDate(now.getDate() - i);
        const dateStr = d.toISOString().slice(0, 10); // YYYY-MM-DD
        dates.push(dateStr);
    }

    // Başlangıç olarak tüm günler sıfır
    const grouped = {};
    dates.forEach(date => {
        grouped[date] = 0;
    });

    items.forEach(item => {
        const dateStr = item.log_date.split(' ')[0];
        if (grouped.hasOwnProperty(dateStr)) {
            grouped[dateStr]++;
        }
    });

    return grouped;
}

window.onload = function() {
    fetch('veri_cek.php')
        .then(response => response.json())
        .then(data => {
            // Attacks
            const attacksByDay = groupByExactLast7Days(data.attacks);
            const attackLabels = Object.keys(attacksByDay);
            const attackCounts = Object.values(attacksByDay);

            const ctx1 = document.getElementById('attacksChart').getContext('2d');
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: attackLabels,
                    datasets: [{
                        label: 'Attack Count (Last 7 Days)',
                        data: attackCounts,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: { display: true, text: 'Gün' }
                        },
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Log Sayısı' }
                        }
                    }
                }
            });

            // Gateway Alarms
            const alarmsByDay = groupByExactLast7Days(data.gateway_alarms);
            const alarmLabels = Object.keys(alarmsByDay);
            const alarmCounts = Object.values(alarmsByDay);

            const ctx2 = document.getElementById('gatewayAlarmsChart').getContext('2d');
            new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: alarmLabels,
                    datasets: [{
                        label: 'Gateway Alarm Count (Last 7 Days)',
                        data: alarmCounts,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: { display: true, text: 'Gün' }
                        },
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Log Sayısı' }
                        }
                    }
                }
            });

            // Successful Logins
            const loginsByDay = groupByExactLast7Days(data.successful_logins);
            const loginLabels = Object.keys(loginsByDay);
            const loginCounts = Object.values(loginsByDay);

            const ctx3 = document.getElementById('successfulLoginsChart').getContext('2d');
            new Chart(ctx3, {
                type: 'bar',
                data: {
                    labels: loginLabels,
                    datasets: [{
                        label: 'Successful Logins Count (Last 7 Days)',
                        data: loginCounts,
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: { display: true, text: 'Gün' }
                        },
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Log Sayısı' }
                        }
                    }
                }
            });

        })
        .catch(error => console.error('Fetch hatası:', error));
};
</script>

</body>
</html>
