<template>
  <v-card>
    <v-card-title>
      {{typeLabel[flags.type]}}
    </v-card-title>
    <GChart
      class="chart"
      :settings="{packages: ['bar', 'corechart']}"
      :data="chartData"
      :options="chartOptions"
      :createChart="(el, google) => new google.visualization.AreaChart(el)"
      @ready="onChartReady"
    />

    <v-card-actions>
      <div uk-grid>
        <div></div>
        <div class="uk-margin">
          <label class="uk-form-label">Період відображення</label>
          <select v-model="flags.period" class="uk-select" @change="getData">
            <option value="daily">Денний</option>
            <option value="weekly">Тижневий</option>
            <option value="monthly">Місячний</option>
            <option value="quarterly">Квартальний</option>
            <option value="yearly">Річний</option>
          </select>
        </div>

        <div class="uk-margin">
          <label class="uk-form-label">Кількість звітів</label>
          <select v-model="flags.count" class="uk-select" @change="getData">
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="50">50</option>
          </select>
        </div>
        <div class="uk-margin">
          <label class="uk-form-label">Тип аналізу</label>
          <select v-model="flags.type" class="uk-select" @change="getData">
            <option value="productivity">Ефективність</option>
            <option value="attendance">Відвідуваність</option>
            <option value="finances">Фінансова динаміка</option>
          </select>
        </div>

      </div>

    </v-card-actions>
  </v-card>
</template>

<script>
import { GChart } from 'vue-google-charts'
export default {
  name: 'App',
  components: {
    GChart
  },
  data () {
    return {
      chartsLib: null,
      chartData: [],
      typeLabel: {
        'productivity': 'Ефективність роботи центру',
        'attendance': 'Відвідуваність центру',
        'finances': 'Фінансова динаміка',
      },
      flags: {
        period: 'weekly',
        count: 10,
        type: 'productivity',
      }
    }
  },
  computed: {
    chartOptions () {
      if (!this.chartsLib) return null
      return this.chartsLib.charts.Bar.convertOptions({

        selectionMode: 'multiple',
        aggregationTarget: 'category',
        hAxis: { format: 'decimal' },
        height: 500,
        colors: ['#80C000', '#FF9400', '#065EA6', '#CE0074'],
        areaOpacity: 0.1
      })
    },
  },
  methods: {
    getData () {
      // console.log(this.flagsReq);
      axios
          .get('/api/v1/stats-get', {params: {type: this.flags.type, count: this.flags.count, period: this.flags.period}})
          .then(response => {
            // console.log(response.data);
            this.chartData = response.data;
          });
    },
    onChartReady (chart, google) {
      this.chartsLib = google
    }
  },
  mounted () {
    this.getData();
  }
}
</script>

<style>
  .chart {
    width: 100%;
    height: 500px;
  }
</style>
