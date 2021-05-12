<template>
  <div>
    <template>
      <v-container>
        <v-row justify="space-around">
          <v-card>
            <v-img
              height="300px"
              src="/bg-tm.jpg"
            >
              <v-app-bar
                flat
                color="rgba(0, 0, 0, 0)"
              >

                <v-chip class="ma-2" color="light-green" text-color="white">
                  Ти із нами з {{getDate(student.created_at)}}
                </v-chip>
                <v-chip class="ma-2" color="orange" text-color="white" href="https://g.page/r/CUq1vi8aM3wiEBE/review" target="_blank">
                  залишити відгук &nbsp;
                  <v-icon> mdi-star-shooting </v-icon>
                </v-chip>

                <v-spacer></v-spacer>
                <v-chip class="ma-2" color="blue-grey" text-color="white">
                  занять відвідано: {{stat.lessons_count}}
                </v-chip>
                <v-chip class="ma-2" color="red" text-color="white">
                  пропусків: {{stat.pass_count}}
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
                  {{student.lname}} {{student.name}}
                </p>
              </v-card-title>
              <span :class="`pa-3 ml-3 ${getBalanceCol(student.balance)} white--text`">
                <v-icon color="white">
                  mdi-cash-usd
                </v-icon>
                поточний баланс {{student.balance}} грн.
              </span>

              <p class="ml-3 white--text pt-3 caption">
                * інформація на сторінці оновлюється раз на добу
              </p>
            </v-img>

            <h4 class="text-center my-3">Онлайн заняття сьогодні
              <v-icon> mdi-cast-education </v-icon>
            </h4>

            <video-rooms></video-rooms>

            <h4 class="text-center my-2">Статистика відвідувань
              <v-icon> mdi-certificate </v-icon>
            </h4>

            <v-card-text>


                <v-row no-gutters>
                  <v-col cols="12" sm="7">
                    <template id="pays">
                      <div class="font-weight-bold ml-8 mb-2 text-center">
                        Недавні заняття
                      </div>
                      <v-timeline align-top>
                        <v-timeline-item
                          v-for="(lesson, index) in lessons"
                          :key="lesson.id"
                          color="green"
                          left
                          small
                          v-if="index < 10"
                        >
                          <div class="text-right">
                            <div class="font-weight-normal">
                              <strong>
                                {{ getDate(lesson.created_at) }}
                              </strong>
                            </div>
                          </div>
                        </v-timeline-item>
                      </v-timeline>
                    </template>
                  </v-col>
                  <v-col cols="12" sm="4">
                    <template id="passes">
                      <div class="font-weight-bold ml-8 mb-2 text-center">
                        Недавні пропуски
                      </div>
                      <v-timeline align-top>
                        <v-timeline-item
                          v-for="(pass, index) in passes"
                          :key="pass.id"
                          color="deep-orange"
                          right
                          small
                          v-if="index < 10"
                        >
                          <div>
                            <div class="font-weight-normal">
                              <strong>
                                {{ getDate(pass.date) }}
                              </strong>
                            </div>
                          </div>
                          <template v-slot:opposite>
                            <span
                              :class="`headline font-weight-bold deep-orange--text`"
                              v-text="`Н`"
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

  </div>

</template>

<script>
  export default {
    props: ['student'],
    data () {
      return {
        lessons: [],
        passes: [],
        stat: {
          lessons_count: 0,
          pass_count: 0,
        }
    }
  },

  mounted () {
    axios
          .get('/api/v1/student-get-profile-stat', {params: {id: this.student.id}})
          .then(response => {
              this.lessons = response.data.pays;
              this.passes = response.data.passes;
              this.stat.lessons_count = response.data.pays_cnt;
              this.stat.pass_count = response.data.passes_cnt;
          });
  },
  methods: {
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

    getBalanceCol (bal) {
      if (bal < 1) {
        return 'red'
      } else if (bal < 500) {
        return 'orange'
      } else {
        return 'green'
      }
    },

  }

}
</script>
