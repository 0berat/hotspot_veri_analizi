<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Raporlar - Grafikler</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      margin: 0;
      padding: 20px;
      background: #f4f6f9;
      color: #333;
    }
    h2 {
      text-align: center;
      font-size: 28px;
      font-weight: bold;
      margin-bottom: 30px;
    }
    .chart-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
    }
    .chart-box {
      background: #fff;
      border: 1px solid #e0e0e0;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      padding: 20px;
      width: 350px;
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: all 0.3s ease;
    }
    .chart-box:hover {
      box-shadow: 0 6px 16px rgba(0,0,0,0.1);
      transform: translateY(-3px);
    }
    .chart-box h3 {
      margin-bottom: 15px;
      font-size: 20px;
      color: #222;
    }
    canvas {
      width: 100% !important;
      height: 260px !important;
    }
  </style>
</head>
<body>

<h2>Log Grafik Raporu</h2>

<div class="chart-container">
  <div class="chart-box">
    <h3>Register Logs</h3>
    <canvas id="registerChart"></canvas>
  </div>
  <div class="chart-box">
    <h3>Auth Errors</h3>
    <canvas id="gatewayAlarmsChart"></canvas>
  </div>
  <div class="chart-box">
    <h3>Successful Logins</h3>
    <canvas id="successfulLoginsChart"></canvas>
  </div>
</div>

<script>
function groupByExactLast7Days(items) {
  const now = new Date();
  const dates = [];

  for (let i = 6; i >= 0; i--) {
    const d = new Date(now);
    d.setDate(now.getDate() - i);
    const dateStr = d.toISOString().slice(0, 10);
    dates.push(dateStr);
  }

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
      createChart('registerChart', groupByExactLast7Days(data.register), 'Register Log Count', 'rgba(255, 99, 132, 0.7)', 'rgba(255, 99, 132, 1)');
      createChart('gatewayAlarmsChart', groupByExactLast7Days(data.gateway_alarms), 'Gateway Alarm Count', 'rgba(54, 162, 235, 0.7)', 'rgba(54, 162, 235, 1)');
      createChart('successfulLoginsChart', groupByExactLast7Days(data.successful_logins), 'Successful Logins Count', 'rgba(75, 192, 192, 0.7)', 'rgba(75, 192, 192, 1)');
    })
    .catch(error => console.error('Fetch hatası:', error));
};

function createChart(canvasId, dataGrouped, label, bgColor, borderColor) {
  const labels = Object.keys(dataGrouped);
  const data = Object.values(dataGrouped);

  const ctx = document.getElementById(canvasId).getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: label,
        data: data,
        backgroundColor: bgColor,
        borderColor: borderColor,
        borderWidth: 1,
        borderRadius: 8,
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          labels: {
            color: '#444',
            font: { size: 13, weight: 'bold' }
          }
        }
      },
      scales: {
        x: {
          ticks: {
            color: '#444',
            font: { size: 11 }
          },
          title: {
            display: true,
            text: 'Gün',
            color: '#666',
            font: { size: 13 }
          }
        },
        y: {
          ticks: {
            color: '#444',
            font: { size: 11 }
          },
          beginAtZero: true,
          title: {
            display: true,
            text: 'Log Sayısı',
            color: '#666',
            font: { size: 13 }
          }
        }
      }
    }
  });
}
</script>

</body>
</html>