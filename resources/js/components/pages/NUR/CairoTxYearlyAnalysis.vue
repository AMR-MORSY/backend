<template>
  <div>
    <Chart
      type="line"
      :data="yearlyData"
      :options="lightOptions"
      :plugins="plugins"
    ></Chart>
  </div>
</template>

<script>
import ChartDataLabels from "chartjs-plugin-datalabels";
export default {
  data() {
    return {
      yearlyData: null,
      lightOptions: {
        plugins: {
          title: {
            display: true,
            text: "Transmission",
          },
          legend: {
            labels: {
              color: "red",
            },
          },
          datalabels: {
            anchor: "top",

            labels: {
              value: {
                color: "black",
              },
            },
          },
        },

        scales: {
          y: {
            suggestedMin: 0,
          },
        },
      },
      plugins: [ChartDataLabels],
    };
  },
  name: "CairoTxYearlyAnalysis",
    inject: ["dialogRef"],
  mounted() {
    this.mountChartData();
  },
  methods: {
    mountChartData() {
      this.yearlyData = {
        labels: this.dialogRef.data.labels,
          datasets: [
          {
            data: Object.values(this.dialogRef.data.cairo),
            label: "Cairo",
            borderColor: "black",
          },
          {
            data: Object.values(this.dialogRef.data.zones["CAIRO EAST"]),
            label: "Cairo East",
            borderColor: "#C3B1E1",
          },
          {
            data: Object.values(this.dialogRef.data.zones["CAIRO SOUTH"]),
            label: "Cairo South",
            borderColor: "red",
          },
          {
            data: Object.values(this.dialogRef.data.zones["CAIRO NORTH"]),
            label: "Cairo North",
            borderColor: "orange",
          },
          {
            data: Object.values(this.dialogRef.data.zones["GIZA"]),
            label: "Giza",
            borderColor: "green",
          },
        ],
      
      };
    },
  },
};
</script>

<style lang="scss" scoped>
</style>