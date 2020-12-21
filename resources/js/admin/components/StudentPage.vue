<template>
  <div>
    <template>
      <v-container>
        <v-row justify="space-around">
          <v-card>
            <v-img
              height="300px"
              src="/tile.jpg"
            >
              <v-app-bar
                flat
                color="rgba(0, 0, 0, 0)"
              >

                <v-toolbar-title class="title white--text pl-0">
                  Учень TutorMath ID:{{student.id}}
                </v-toolbar-title>

                <v-chip class="ma-2" color="light-green" text-color="white">
                  із нами з {{getDate(student.created_at)}}
                </v-chip>

                <v-spacer></v-spacer>
                <v-chip class="ma-2" color="blue-grey" text-color="white">
                  занять оплачено: {{pays.length}}
                </v-chip>
                <v-chip class="ma-2" color="blue-grey" text-color="white">
                  <v-icon color="white">
                    mdi-currency-usd
                  </v-icon>
                  {{getSumm}}
                </v-chip>
                <v-chip class="ma-2" color="red" text-color="white">
                  пропусків: {{passes.length}}
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
                  {{student.concname}}
                </p>
              </v-card-title>
              <p class="ml-3 white--text">
                <v-icon color="white">
                  mdi-cash-usd
                </v-icon>
                {{student.balance}}
                <v-icon color="white">
                  mdi-phone
                </v-icon>
                {{student.phone}}
              </p>

              <p class="ml-3 white--text float--right">
                {{student.comment}}
              </p>
            </v-img>

            <v-card-text>

                <v-row no-gutters>
                  <v-col cols="12" sm="7">
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
                          <div>
                            <div class="font-weight-normal">
                              <strong>
                                {{ payLabel[pay.type] }}
                              </strong> @{{ getDate(pay.created_at) }}
                            </div>
                            <div>{{ pay.sum }}</div>
                          </div>
                        </v-timeline-item>
                      </v-timeline>
                    </template>
                  </v-col>
                  <v-col cols="12" sm="4">
                    <template id="passes">
                      <div class="font-weight-bold ml-8 mb-2 text-center">
                        Історія пропусків
                      </div>
                      <v-timeline align-top>
                        <v-timeline-item
                          v-for="pass in passes"
                          :key="pass.id"
                          color="red"
                          right
                          small
                        >
                          <div>
                            <div class="font-weight-normal">
                              <strong>
                                {{ getDate(pass.date) }}
                              </strong>
                            </div>
                          </div>
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

  </div>

</template>

<script>
  export default {
    props: ['student'],
    data () {
      return {
        payLabel: {
          'lesson-pay': 'Оплата заняття',
          'refill': 'Внесення коштів',
        },
        pays: [],
        passes: [],
    }
  },

  mounted () {
    axios
          .get('/api/v1/student-get-stat', {params: {id: this.student.id}})
          .then(response => {
              this.pays = response.data.pays;
              this.passes = response.data.passes;
          });
  },
  computed: {
    getSumm () {
      let sum = 0;
      this.pays.forEach((pay, i) => {
        sum = sum + pay.sum
      });
      return sum;
    }
  },
  methods: {
    getColor (type) {
      return type === 'refill' ? 'green' : 'red'
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
      return type === 'refill'
    },

  }

}
</script>
