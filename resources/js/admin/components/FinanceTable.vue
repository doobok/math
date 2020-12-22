<template>
  <div>
    <v-dialog
      v-model="dialog"
      max-width="400px"
    >
    <validation-observer ref="observer" v-slot="{ invalid }">
    <v-card>
      <v-card-title>
        <span class="headline">Внести видаток</span>
      </v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="10">
              <validation-provider :rules="{required: true, integer: true}" v-slot="{ errors }">
                <v-text-field
                  v-model="paySum"
                  :error-messages="errors"
                  label="Сума"
                ></v-text-field>
              </validation-provider>
            </v-col>
            <v-col cols="10">
              <validation-provider rules="required" v-slot="{ errors }">
                <v-text-field
                  v-model="payComment"
                  :error-messages="errors"
                  label="Коментар (за що оплата)"
                ></v-text-field>
              </validation-provider>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          color="red darken-1"
          text
          @click="closeDialog"
        >
          Скасувати
        </v-btn>
        <v-btn
          color="blue darken-1"
          text
          :disabled="invalid"
          @click="paySet()"
        >
          внести
        </v-btn>
      </v-card-actions>
    </v-card>
    </validation-observer>
    </v-dialog>
  <v-card>
    <v-card-title>
      TutorMath
      <v-btn
        class="ml-4"
        elevation="2"
        icon
        @click="dialogOpn"
      ><v-icon>mdi-plus</v-icon>
      </v-btn>
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
        <div class="" v-html="getUser(item)">
        </div>
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
          'lesson-pay': 'Оплата за заняття',
          'lesson-wage': 'Комісія тьютора',
          'refill': 'Внесення оплати',
          'wage': 'Зарплата тьютора',
          'other-pay': 'Інші видатки',
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
          { text: 'Коментар', value: 'comment' },
          { text: 'Користувач', value: 'user', sortable: false },
          { text: 'Дата', value: 'created_at' },
        ],
        items: [],
        skiped: 0,
        download: true,
        dialog: false,
        paySum: '',
        payComment: '',
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
              if (response.data.length < 50) {
                this.download = false;
              }
              this.items = [].concat(this.items, response.data);
              this.skiped = this.skiped + 50;
            });
      },
      getVectorColor (type) {
        if (type === 'refill') {
          return 'green'
        } else if (type === 'lesson-pay') {
          return 'green lighten-3'
        } else if (type === 'lesson-wage') {
          return 'pink lighten-3'
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
          return `<a href="/students/${item.student_id}">Учень з ID:${item.student_id}</a>`
          // return a 'Учень з ID:'+ item.student_id
        } else if (item.tutor_id) {
          return `<a href="/tutors/${item.tutor_id}">Tutor ID:${item.tutor_id}</a>`
        } else {
          return `<span> NA </span>`
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
      dialogOpn () {
        this.dialog = true;
      },
      closeDialog () {
        this.dialog = false;
        this.paySum = '';
        this.payComment = '';
      },
      paySet () {
        axios
          .post('/api/v1/other-pay-add', {sum: this.paySum, comment: this.payComment})
          .then(response => {
            if (response.data.success === true) {
              console.log(response.data);

              this.items.unshift(response.data.pay);

              this.closeDialog();
            }
          })
          .catch(err => {
            let e = { ...err    }
            console.log(e);
            alert('Виникла помилка, повторіть спробу трішки пізніше');
          });
      }
    },


}
</script>
