<template>
  <v-card>
    <v-card-title>
      Ефективність
    </v-card-title>
    <GChart
      class="chart3"
      :settings="{packages: ['bar', 'corechart']}"
      :data="chartData"
      :options="chartOptions"
      :createChart="(el, google) => new google.visualization.AreaChart(el)"
      @ready="onChartReady"
    />

    <v-card-actions>
      <div uk-grid>
        <div></div>
        <div class="mx-2 mb-4">
          <v-btn
            elevation="2"
            class="mr-2"
            @click="reloadGraph('l')"
          >
          <v-icon> mdi-school </v-icon>
          Заняття
          </v-btn>
          <v-btn
            elevation="2"
            class="mr-2"
            @click="reloadGraph('p')"
          >
          <v-icon> mdi-currency-usd </v-icon>
          Фінанси
          </v-btn>
          <v-btn
            elevation="2"
            @click="reloadGraph('kpi')"
          >
          <v-icon> mdi-chart-histogram </v-icon>
          KPI
          </v-btn>
        </div>


      </div>

    </v-card-actions>

  </v-card>
</template>

<script>
import { GChart } from 'vue-google-charts'
export default {
  props: ['model', 'mid'],
  name: 'App',
  components: {
    GChart
  },
  data () {
    return {
      chartsLib: null,
      chartData: [],
      flag: 'p',
    }
  },
  computed: {
    chartOptions () {
      if (!this.chartsLib) return null
      return this.chartsLib.charts.Bar.convertOptions({

        selectionMode: 'multiple',
        aggregationTarget: 'category',
        hAxis: { format: 'decimal', textPosition: 'none' },
        height: 300,
        colors: ['#80C000', '#FF9400', '#065EA6', '#CE0074'],
        areaOpacity: 0.1
      })
    },
  },
  methods: {
    getData () {
      axios
          .get('/api/v1/kpi-get', {params: {model: this.model, id: this.mid, f: this.flag}})
          .then(response => {
            this.chartData = response.data;
          });
    },
    onChartReady (chart, google) {
      this.chartsLib = google
    },
    reloadGraph(flag) {
      this.flag = flag;
      this.getData();
    }
  },
  mounted () {
    this.getData();
  }
}
</script>

<style>
  .chart3 {
    width: 100%;
    height: 300px;
  }
</style>
