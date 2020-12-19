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
      <template v-slot:item.type="{ item }">
        {{ nameLabel[item.type] }}
      </template>
      <template v-slot:item.vector="{ item }">
        <v-icon
        :color="getVectorColor(item.type)"
        >{{ getVector(item.type) }}</v-icon>
      </template>
      <template v-slot:item.user="{ item }">
        {{ getUser(item) }}
      </template>
      <template v-slot:item.created_at="{ item }">
        {{ getDate(item.created_at) }}
      </template>

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
        nameLabel: {
          'lesson-pay': 'Олата за заняття',
          'lesson-wage': 'Комісія тьютора',
          'refill': 'Внесення оплати',
          'wage': 'Зарплата тьютора',
        },
        search: '',
        headers: [
          {
            text: 'Тип',
            align: 'start',
            value: 'type',
          },
          { text: ' ', value: 'vector', sortable: false },
          { text: 'Сума', value: 'sum' },
          { text: 'Користувач', value: 'user', sortable: false },
          { text: 'Дата', value: 'created_at' },
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
            .get('/api/v1/finances-get', {params: {skip: this.skiped}})
            .then(response => {
              console.log(response.data.length);
              if (response.data.length < 50) {
                this.download = false;
              }
              this.items = [].concat(this.items, response.data);
              this.skiped = this.skiped + 50;
            });
      },
      getVectorColor (type) {
        if (type === 'lesson-pay' || type === 'refill') {
          return 'green'
        } else {
          return 'red'
        }
      },
      getVector (type) {
        if (type === 'lesson-pay' || type === 'refill') {
          return 'mdi-arrow-left-bold'
        } else {
          return 'mdi-arrow-right-bold'
        }
      },
      getUser (item) {
        if (item.student_id) {
          return 'Учень з ID:'+ item.student_id
        } else if (item.tutor_id) {
          return 'Tutor з ID:'+ item.tutor_id
        } else {
          return 'невідомий'
        }
      },
      getDate (date) {
        let d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;

        return [day, month, year].join('-');
      },
    },


}
</script>
