<template>
  <Line :data="chartData" :options="chartOptions" />
</template>

<script setup>
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend } from 'chart.js';

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend);

const props = defineProps({
  data: {
    type: Object,
    required: true
  }
});

const months = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

const chartData = {
  labels: months,
  datasets: [{
    label: 'Consultas',
    data: months.map((_, index) => props.data[index + 1] || 0),
    borderColor: '#4F46E5',
    tension: 0.1
  }]
};

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false
};
</script>