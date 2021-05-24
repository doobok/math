<template>
  <div>
    <template>
      <v-container>
        <v-row justify="space-around">
          <v-card>
            <v-img
              height="300px"
              src="/tile-orange.jpg"
            >
              <v-app-bar
                flat
                color="rgba(0, 0, 0, 0)"
              >

                <v-toolbar-title class="title white--text pl-0">
                  Тьютор TutorMath ID:{{tutor.id}}
                </v-toolbar-title>
                <v-chip class="ma-2" color="light-green" text-color="white">
                  із нами з {{getDate(tutor.created_at)}}
                </v-chip>
                <v-spacer></v-spacer>
                <v-chip class="ma-2" color="blue" text-color="white">
                  Всього занять
                  <v-icon color="white" class="ma-1">
                    mdi-book-education
                  </v-icon>
                  {{lessons}}
                </v-chip>
                <v-chip class="ma-2" color="blue-grey" text-color="white">
                  виплачено
                  <v-icon color="white">
                    mdi-currency-usd
                  </v-icon>
                  {{sum}} грн
                </v-chip>
                <v-chip class="ma-2" color="indigo" text-color="white">
                  <v-icon color="white" class="mr-1">
                    mdi-format-align-middle
                  </v-icon>
                  {{averageSum()}} грн./заняття
                </v-chip>

              </v-app-bar>

              <v-card-title class="white--text mt-8">
                <v-avatar size="56">
                  <v-btn elevation="5" x-large icon color="white">
                    <v-icon x-large>
                      mdi-account
                    </v-icon>
                  </v-btn>
                </v-avatar>
                <p class="ml-3">
                  {{tutor.lname}} {{tutor.name}} {{tutor.mname}}
                </p>
              </v-card-title>
              <p class="ml-3 white--text">
                <v-icon color="white">
                  mdi-cash-usd
                </v-icon>
                {{tutor.balance}}
                <v-btn class="ml-3" outlined small color="white" @click="openDialog()">
                  виплатити заробітну плату
                </v-btn>
                <v-icon color="white">
                  mdi-phone
                </v-icon>
                {{tutor.phone}}
              </p>

              <p class="ml-3 white--text float--right">
                {{tutor.comment}}
              </p>
            </v-img>

            <v-card-text>

                <v-row no-gutters>
                  <v-col cols="12" sm="12">
                    <template id="pays">
                      <div class="font-weight-bold ml-8 mb-2 text-center">
                        Фінансова історія
                      </div>
                      <v-timeline align-top>
                        <v-timeline-item
                          v-for="pay in pays"
                          :key="pay.id"
                          :color="getColor(pay.type)"
                          right
                          :left="getLeft(pay.type)"
                          small
                        >
                          <div :class="getSide(pay.type)">
                            <div>
                              <strong>
                                {{ payLabel[pay.type] }}
                              </strong>
                            </div>
                            <div>{{ getDate(pay.created_at) }}</div>
                          </div>
                          <template v-slot:opposite>
                            <span
                              class="headline font-weight-bold gray--text"
                              v-text="pay.sum"
                            ></span>
                          </template>
                        </v-timeline-item>
                      </v-timeline>
                    </template>
                  </v-col>
                </v-row>

            </v-card-text>
          </v-card>
        </v-row>
      </v-container>
    </template>

    <v-dialog
      v-model="wageDialog"
      max-width="310px"
    >
    <validation-observer ref="observer" v-slot="{ invalid }">
    <v-card>
      <v-card-title>
        <span class="headline">Виплатити ЗП</span>
      </v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="9">На балансі {{tutor.balance}} грн. <br> залишиться {{tutor.balance - wageSum}}</v-col>
            <v-col cols="7">
              <validation-provider :rules="{required: true, integer: true}" v-slot="{ errors }">
                <v-text-field
                  v-model="wageSum"
                  :error-messages="errors"
                  label="Сума"
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
          @click="payWage()"
        >
          виплатити
        </v-btn>
      </v-card-actions>
    </v-card>
    </validation-observer>
    </v-dialog>

  </div>

</template>

<script>
  export default {
    props: ['tutor'],
    data () {
      return {
        payLabel: {
          'lesson-wage': 'Оплата за заняття',
          'wage': 'Виплата заробітної плати',
        },
        pays: [],
        sum: 0,
        lessons: 0,
        wageDialog: false,
        wageSum: '',
    }
  },

  mounted () {
    axios
          .get('/api/v1/tutor-get-stat', {params: {id: this.tutor.id}})
          .then(response => {
              this.pays = response.data.pays;
              this.sum = response.data.sum;
              this.lessons = response.data.lessonscount;
          });
  },
  methods: {
    getColor (type) {
      return type === 'lesson-wage' ? 'green' : 'red'
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
    getLeft (type) {
      return type === 'lesson-wage'
    },
    getSide (type) {
      if (type === 'lesson-wage') {
        return 'text-right'
      } else {
        return 'text-left'
      }
    },
    openDialog () {
      this.wageDialog = true;
    },
    closeDialog () {
      this.wageDialog = false;
      this.wageSum = '';
    },
    payWage () {
      axios
        .post('/api/v1/wage-pay', {sum: this.wageSum, id: this.tutor.id})
        .then(response => {
          if (response.data.success === true) {
            this.tutor.balance = this.tutor.balance - this.wageSum;
            this.sum = +this.sum + +this.wageSum;
            this.pays.unshift(response.data.pay);
            this.closeDialog();
          }
        })
        .catch(err => {
          let e = { ...err    }
          console.log(e);
          alert('Виникла помилка, повторіть спробу трішки пізніше');
        });

    },
    averageSum () {
      let avsum;
      avsum = (this.sum + this.tutor.balance) / this.lessons;
      return avsum.toFixed(0);
    }

  }

}
</script>
