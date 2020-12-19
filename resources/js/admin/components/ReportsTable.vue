<template>
  <div>
  <v-card>
    <v-card-title>
      TutorMath
      <v-spacer></v-spacer>
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Пошук"
        single-line
        hide-details
      ></v-text-field>
    </v-card-title>
    <v-data-table
      :headers="headers"
      :items="items"
      :search="search"
      >
    </v-data-table>
  </v-card>
  <v-toolbar
    v-if="download"
    flat
  >

    <v-spacer></v-spacer>

    <v-btn elevation="0" @click="getData">
      завантажити ще
      <v-icon >
        mdi-download
      </v-icon>
    </v-btn>


  </v-toolbar>
</div>
</template>

<script>
  export default {
    data () {
      return {
        typeLabel: {
          'daily': 'Денний',
          'weekly': 'Тижневий',
          'monthly': 'Місячний',
          'quarterly': 'Квартальний',
        },
        search: '',
        headers: [
          {
            text: 'Тип',
            align: 'start',
            value: 'type',
          },
          { text: 'Період', value: 'period' },
          { text: 'Заняття', value: 'lessons' },
          { text: 'ЗП', value: 'wage' },
          { text: 'Профіт', value: 'profit' },
          { text: 'Уроків', value: 'lessons_count' },
          { text: 'Учнів', value: 'students_count' },
          { text: 'Пропусків', value: 'pass_count' },
          { text: 'БО', value: 'pass_notpayed_count' },
          { text: 'Надходження', value: 'pays_in' },
          { text: 'Видатки', value: 'pays_out' },
          { text: 'Каса', value: 'pays_profit' },
          { text: 'Помилки', value: 'errors' },
        ],
        items: [],
        skiped: 0,
        download: true,
    }
  },
  mounted () {
    this.getData();
  },
  methods: {
      getData () {
        axios
            .get('/api/v1/reports-get', {params: {skip: this.skiped}})
            .then(response => {
              console.log(response.data.length);
              if (response.data.length < 50) {
                this.download = false;
              }
              this.items = [].concat(this.items, response.data);
              this.skiped = this.skiped + 50;
            });
      },
    },
}
</script>
