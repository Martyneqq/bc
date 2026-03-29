<script setup lang="ts">
import { Line } from 'vue-chartjs'
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend } from 'chart.js'

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend)

interface DataPoint {
  date: string
  value: number
}

interface Props {
  data: DataPoint[]
  title?: string
  label?: string
  color?: string
}

const props = withDefaults(defineProps<Props>(), {
  title: 'Income/Expense Trend',
  label: 'Amount',
  color: '#3B82F6',
})

/**
 * Calculate simple linear regression
 * Returns array with predicted values
 */
function calculateLinearRegression(data: DataPoint[]): { predictions: number[]; equation: string } {
  if (data.length < 2) return { predictions: data.map(d => d.value), equation: 'N/A' }

  const n = data.length
  const x = Array.from({ length: n }, (_, i) => i)
  const y = data.map(d => d.value)

  const xMean = x.reduce((a, b) => a + b, 0) / n
  const yMean = y.reduce((a, b) => a + b, 0) / n

  const numerator = x.reduce((sum, xi, i) => sum + (xi - xMean) * (y[i] - yMean), 0)
  const denominator = x.reduce((sum, xi) => sum + (xi - xMean) ** 2, 0)

  const slope = denominator !== 0 ? numerator / denominator : 0
  const intercept = yMean - slope * xMean

  const predictions = x.map(xi => slope * xi + intercept)
  const equation = `y = ${slope.toFixed(2)}x + ${intercept.toFixed(2)}`

  return { predictions, equation }
}

const { predictions, equation } = calculateLinearRegression(props.data)

const chartData = {
  labels: props.data.map(d => new Date(d.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
  datasets: [
    {
      label: `${props.label} (Actual)`,
      data: props.data.map(d => d.value),
      borderColor: props.color,
      backgroundColor: `${props.color}20`,
      fill: true,
      tension: 0.4,
      pointRadius: 5,
      pointHoverRadius: 7,
      borderWidth: 2,
    },
    {
      label: 'Linear Trend',
      data: predictions,
      borderColor: '#EF4444',
      borderDash: [5, 5],
      borderWidth: 2,
      fill: false,
      pointRadius: 0,
      tension: 0.4,
    },
  ],
}

const chartOptions = {
  responsive: true,
  maintainAspectRatio: true,
  plugins: {
    title: {
      display: true,
      text: `${props.title} (${equation})`,
      font: {
        size: 14,
        weight: 'bold' as const,
      },
    },
    legend: {
      display: true,
      position: 'top' as const,
    },
    tooltip: {
      backgroundColor: 'rgba(0, 0, 0, 0.8)',
      padding: 12,
      titleFont: {
        size: 12,
      },
      bodyFont: {
        size: 12,
      },
    },
  },
  scales: {
    y: {
      beginAtZero: true,
      ticks: {
        callback: function (value: number) {
          return '€ ' + value.toFixed(2)
        },
      },
    },
  },
}
</script>

<template>
  <div class="chart-container">
    <Line :data="chartData" :options="chartOptions" />
    <p class="chart-info">
      Regression equation: <strong>{{ equation }}</strong>
    </p>
  </div>
</template>

<style scoped>
.chart-container {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.chart-info {
  margin-top: 1rem;
  font-size: 0.875rem;
  color: #666;
  text-align: center;
}
</style>
