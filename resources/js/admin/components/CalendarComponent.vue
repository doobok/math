<template>
  <div>
    <v-dialog
      v-model="editForm"
      persistent
      width="800"
      style="overflow-x: hidden"
    >
      <v-card>
        <v-card-title class="headline">{{ formTitle }}</v-card-title>

        <v-card-text>

          <v-container>
            <v-row>
              <v-col class="d-flex" cols="10" sm="5">
                <v-select
                  v-model="editedItem.tutor_id"
                  :items="tutors"
                  :hint="`${editedItem.tutor_id.lname} ${editedItem.tutor_id.name} ${editedItem.tutor_id.mname}`"
                  item-text="lname"
                  label="Тьютор"
                  item-value="id"
                  return-object
                ></v-select>
              </v-col>
              <v-col class="d-flex" cols="10" sm="5">
                <v-select
                  v-model="editedItem.classroom_id"
                  :items="classrooms"
                  item-text="name"
                  item-value="id"
                  label="Кабінет"
                ></v-select>
              </v-col>
              <v-col class="d-flex" cols="8" sm="4">
                <v-text-field
                  v-model="editedItem.price_student"
                  prepend-icon="mdi-cash-multiple"
                  label="Вартість для учня"
                ></v-text-field>
              </v-col>
              <v-col class="d-flex" cols="8" sm="4">
                <v-text-field
                  v-model="editedItem.price_tutor"
                  prepend-icon="mdi-cash-multiple"
                  label="Комісія тьютора"
                ></v-text-field>
              </v-col>
              <v-col class="d-flex" cols="9" sm="3">
                  <v-menu
                    v-model="startPicD"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="datePic.date"
                        label="Дата"
                        prepend-icon="mdi-calendar"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      v-model="datePic.date"
                      @input="startPicD = false"
                    ></v-date-picker>
                  </v-menu>
                </v-col>
                <v-col class="d-flex" cols="9" sm="3">
                  <v-dialog
                    ref="dialog1"
                    v-model="startPicT"
                    :return-value.sync="datePic.start"
                    persistent
                    width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="datePic.start"
                        label="Час початку"
                        prepend-icon="mdi-clock-time-four-outline"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-time-picker
                      v-if="startPicT"
                      v-model="datePic.start"
                      format="24hr"
                      full-width
                    >
                      <v-spacer></v-spacer>
                      <v-btn text color="primary" @click="startPicT = false"
                      > Скасувати </v-btn>
                      <v-btn text color="primary"
                        @click="$refs.dialog1.save(datePic.start)"
                      > OK </v-btn>
                    </v-time-picker>
                  </v-dialog>
                </v-col>
                <v-col class="d-flex" cols="9" sm="3">
                  <v-dialog
                    ref="dialog2"
                    v-model="endPicT"
                    :return-value.sync="datePic.end"
                    persistent
                    width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="datePic.end"
                        label="Час закінчення"
                        prepend-icon="mdi-clock-time-four-outline"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-time-picker
                      v-if="endPicT"
                      v-model="datePic.end"
                      format="24hr"
                      full-width
                    >
                      <v-spacer></v-spacer>
                      <v-btn text color="primary" @click="endPicT = false"
                      > Скасувати </v-btn>
                      <v-btn text color="primary"
                        @click="$refs.dialog2.save(datePic.end)"
                      > OK </v-btn>
                    </v-time-picker>
                  </v-dialog>
                </v-col>
                <v-col class="d-flex" cols="10">
                  <v-select
                    v-model="editedItem.students"
                    :items="students"
                    label="Учні"
                    item-text="concname"
                    item-value="id"
                    chips
                    multiple
                    return-object
                  ></v-select>
                </v-col>
                <v-col class="d-flex" cols="10" sm="4">
                    <v-menu
                      v-model="lastDatePic"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      transition="scale-transition"
                      offset-y
                      min-width="290px"
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                          v-model="datePic.last"
                          label="Заняття до"
                          prepend-icon="mdi-calendar"
                          readonly
                          v-bind="attrs"
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker
                        v-model="datePic.last"
                        @input="lastDatePic = false"
                      ></v-date-picker>
                    </v-menu>
                  </v-col>
              <v-col cols="10">
                <v-text-field
                  v-model="editedItem.comment"
                  label="Коментар"
                ></v-text-field>
              </v-col>

            </v-row>
          </v-container>

        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn color="red darken-1" text @click="close">
            Скасувати
          </v-btn>

          <v-btn color="blue darken-1" text @click="save">
            зберегти
          </v-btn>

        </v-card-actions>
      </v-card>
    </v-dialog>

  <v-row class="fill-height">
    <v-col>
      <v-sheet height="64">
        <v-toolbar
          flat
        >
          <v-btn
            outlined
            class="mr-4"
            color="grey darken-2"
            @click="setToday"
          >
            Сьогодні
          </v-btn>
          <v-btn
            fab
            text
            small
            color="grey darken-2"
            @click="prev"
          >
            <v-icon small>
              mdi-chevron-left
            </v-icon>
          </v-btn>
          <v-btn
            fab
            text
            small
            color="grey darken-2"
            @click="next"
          >
            <v-icon small>
              mdi-chevron-right
            </v-icon>
          </v-btn>
          <v-toolbar-title v-if="$refs.calendar">
            {{ $refs.calendar.title }}
          </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-menu
            bottom
            right
          >
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                outlined
                color="grey darken-2"
                v-bind="attrs"
                v-on="on"
              >
                <span>{{ typeToLabel[type] }}</span>
                <v-icon right>
                  mdi-menu-down
                </v-icon>
              </v-btn>
            </template>
            <v-list>
              <v-list-item @click="type = 'day'">
                <v-list-item-title>День</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'week'">
                <v-list-item-title>Тиждень</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'month'">
                <v-list-item-title>Місяць</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = '4day'">
                <v-list-item-title>4 дні</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-toolbar>
      </v-sheet>
      <v-sheet height="900">
        <v-calendar
          ref="calendar"
          v-model="focus"
          color="primary"
          weekdays="1, 2, 3, 4, 5, 6, 0"
          locale="uk"
          interval-height="50"
          first-interval="8"
          interval-count="13"
          :events="lessons"
          :event-color="getEventColor"
          :type="type"
          @click:event="showEvent"
          @click:more="viewDay"
          @click:date="viewDay"
          @change="updateRange"


          @mousedown:event="startDrag"
          @mousedown:time="startTime"
          @mousemove:time="mouseMove"
          @mouseup:time="endDrag"
          @mouseleave.native="cancelDrag"

        >
        <template v-slot:event="{ event, timed, eventSummary }">
          <div
            class="v-event-draggable"
            v-html="eventSummary()"
          ></div>
          <div
            v-if="timed"
            class="v-event-drag-bottom"
            @mousedown.stop="extendBottom(event)"
          ></div>
        </template>

      </v-calendar>
        <v-menu
          v-model="selectedOpen"
          :close-on-content-click="false"
          :activator="selectedElement"
          offset-x
        >
          <v-card
            color="grey lighten-4"
            min-width="350px"
            flat
          >
            <v-toolbar
              :color="selectedEvent.color"
              dark
            >
              <v-btn @click="edit" icon>
                <v-icon>mdi-pencil</v-icon>
              </v-btn>
              <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
              <v-spacer></v-spacer>
              <v-btn icon>
                <v-icon>mdi-dots-vertical</v-icon>
              </v-btn>
            </v-toolbar>
            <v-card-text>
              <span>Тьютор: {{getTutorbyId}}</span><br>
              <span>Кабінет: {{getClassbyId}}</span><br>
              <span v-html="selectedEvent.comment"></span>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn text color="secondary" @click="selectedOpen = false">
                Ok
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-menu>

        <!-- <calendar-addleson :startTime="new Date(this.createStart)"></calendar-addleson> -->


      </v-sheet>
    </v-col>
  </v-row>
</div>
</template>

<script>
  export default {
    data: () => ({
      focus: '',
      type: 'week',
      typeToLabel: {
        month: 'Місяць',
        week: 'Тиждень',
        day: 'День',
        '4day': '4 дні',
      },
      selectedEvent: {},
      selectedElement: null,
      selectedOpen: false,
      events: [],
      colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
      names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party'],
      editForm: false,
      editedIndex: -1,
      editedItem: {
        id: '',
        name: '',
        start: '',
        end: '',
        students: '',
        price_student: '',
        price_tutor: '',
        tutor_id: '',
        classroom_id: '',
        period_end: '',
        comment: '',
        color: '',
      },
      defaultItem: {
        id: '',
        name: '',
        start: '',
        end: '',
        students: '',
        price_student: '',
        price_tutor: '',
        tutor_id: '',
        classroom_id: '',
        period_end: '',
        comment: '',
        color: '',
      },
      datePic: {
        date: '',
        start: '',
        end: '',
        last: '',
      },
      students: [],
      tutors: [],
      classrooms: [],
      startPicD: false,
      startPicT: false,
      endPicT: false,
      lastDatePic: false,
      selectedStud: [],
    }),
    mounted () {
      axios
        .get('/api/v1/lesson-start-data')
        .then(response => {
          console.log(response);
          this.students = response.data.students;
          this.tutors = response.data.tutors;
          this.classrooms = response.data.classrooms;
        })
        .catch(err => {
          let e = { ...err    }
          console.log(e);
          alert('Виникла помилка, повторіть спробу трішки пізніше');
        });

        // получение состояния тасков
        // this.events = this.$store.getters.lessons;

        // this.$store.dispatch('GET_LESSONS');


      this.$refs.calendar.checkChange()
    },
    methods: {
      // закрыть форму редактирования
      close () {
        this.editForm = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
          this.datePic.last = ''
        })
      },
      // нажатие на редактирование события
      edit () {
        console.log('edit worked');
        this.editedIndex =  this.lessons.indexOf(this.selectedEvent);

        this.editForm = true;
        this.editedItem = this.selectedEvent;
        this.datePic.date = this.formatDate(this.selectedEvent.start);
        this.datePic.start = this.formatTime(this.selectedEvent.start);
        this.datePic.end = this.formatTime(this.selectedEvent.end);
        if (this.selectedEvent.period_end) {
          this.datePic.last = this.formatDate(this.selectedEvent.period_end);
        }

        this.editedItem.students = JSON.parse(this.selectedEvent.students);

      },
      // сохранить форму
      save () {
        console.log(this.editedItem.students);

        this.editedItem.start = this.datePic.date + ' ' + this.datePic.start;
        this.editedItem.end = this.datePic.date + ' ' + this.datePic.end;

        if (this.editedIndex > -1) {
            this.$store.dispatch('EDIT_LESSON', this.collector);
            // Object.assign(this.lessons[this.editedIndex], this.editedItem)
        } else {
          console.log( this.editedItem);
          this.$store.dispatch('SET_LESSON', this.collector);

        }
        this.close()
      },
      viewDay ({ date }) {
        console.log('Показать день');//норм
        this.focus = date
        this.type = 'day'
      },
      getEventColor (event) {
        return event.color
      },
      setToday () {
        this.focus = ''
      },
      prev () {
        this.$refs.calendar.prev()
      },
      next () {
        this.$refs.calendar.next()
      },
      showEvent ({ nativeEvent, event }) {
        console.log('Клик по событию');//норм
        // setTimeout(() => {
          const open = () => {
            this.selectedEvent = event
            this.selectedElement = nativeEvent.target
            setTimeout(() => {
              this.selectedOpen = true
            }, 10)
          }
        // }, 100)

        if (this.selectedOpen) {
          this.selectedOpen = false
          setTimeout(open, 10)
        } else {
          open()
        }

        nativeEvent.stopPropagation()
      },
      updateRange ({ start, end }) {
        this.$store.dispatch('GET_LESSONS');
        console.log('get lessons');
        // console.log(start);

      //   console.log('Изменения состояния');//норм
      //
      //   const min = new Date(`${start.date}T00:00:00`)
      //   const max = new Date(`${end.date}T23:59:59`)
      //
      //   // // создание случайных событий
      //   // const events = []
      //   //
      //   const days = (max.getTime() - min.getTime()) / 86400000
      //   // const eventCount = this.rnd(days, days + 20)
      //   //
      //   // for (let i = 0; i < eventCount; i++) {
      //   //   const allDay = this.rnd(0, 3) === 0
      //   //   const firstTimestamp = this.rnd(min.getTime(), max.getTime())
      //   //   const first = new Date(firstTimestamp - (firstTimestamp % 900000))
      //   //   const secondTimestamp = this.rnd(2, allDay ? 288 : 8) * 900000
      //   //   const second = new Date(first.getTime() + secondTimestamp)
      //   //
      //   //   events.push({
      //   //     name: this.names[this.rnd(0, this.names.length - 1)],
      //   //     start: first,
      //   //     end: second,
      //   //     color: this.colors[this.rnd(0, this.colors.length - 1)],
      //   //     timed: !allDay,
      //   //   })
      //   // }
      //   //
        this.events = this.lessons;
      },
      rnd (a, b) {
        return Math.floor((b - a + 1) * Math.random()) + a
      },
      startDrag ({ event, timed }) {
        console.log('Начало перетаскивания');
        if (event && timed) {
          this.dragEvent = event
          this.dragTime = null
          this.extendOriginal = null
        }
      },
      startTime (tms) {
        console.log('Мышка опустилась на шкалу времени');

        const mouse = this.toTime(tms)

        if (this.dragEvent && this.dragTime === null) {
          const start = this.dragEvent.start

          this.dragTime = mouse - start
        } else {
          this.createStart = this.roundTime(mouse)
          this.editForm = true;
          this.datePic.date = this.formatDate(this.createStart);
          this.datePic.start = this.formatTime(this.createStart);
          this.datePic.end = this.formatTime(this.createStart+3600000);
          // this.editedItem.start = new Date(this.createStart);
          // alert(new Date(this.createStart))
          // this.createEvent = {
          //   name: `Event #${this.events.length}`,
          //   color: this.rndElement(this.colors),
          //   start: this.createStart,
          //   end: this.createStart,
          //   timed: true,
          // }

          // this.events.push(this.createEvent)
        }
      },
      extendBottom (event) {
        this.createEvent = event
        this.createStart = event.start
        this.extendOriginal = event.end
      },
      mouseMove (tms) {
        // console.log('Мышка перемещается');//Перемішення вказівника миші
        const mouse = this.toTime(tms)

        if (this.dragEvent && this.dragTime !== null) {
          const start = this.dragEvent.start
          const end = this.dragEvent.end
          const duration = end - start
          const newStartTime = mouse - this.dragTime
          const newStart = this.roundTime(newStartTime)
          const newEnd = newStart + duration

          this.dragEvent.start = newStart
          this.dragEvent.end = newEnd
        } else if (this.createEvent && this.createStart !== null) {
          const mouseRounded = this.roundTime(mouse, false)
          const min = Math.min(mouseRounded, this.createStart)
          const max = Math.max(mouseRounded, this.createStart)

          this.createEvent.start = min
          this.createEvent.end = max
        }
      },
      endDrag () {
        console.log('Конец перемещения');
        if (this.dragEvent && this.dragTime !== null) {
          console.log('новое время события:' + this.dragEvent.start + ' по ' + this.dragEvent.end);

          // this.dragEvent.start = newStart
          // this.dragEvent.end = newEnd
        }
        this.dragTime = null
        this.dragEvent = null
        this.createEvent = null
        this.createStart = null
        this.extendOriginal = null
      },
      cancelDrag () {
        console.log('Мышка вне таблицы');
        if (this.createEvent) {
          if (this.extendOriginal) {
            this.createEvent.end = this.extendOriginal
          } else {
            const i = this.events.indexOf(this.createEvent)
            if (i !== -1) {
              this.events.splice(i, 1)
            }
          }
        }

        this.createEvent = null
        this.createStart = null
        this.dragTime = null
        this.dragEvent = null
      },
      roundTime (time, down = true) {
        const roundTo = 30 // minutes
        const roundDownTime = roundTo * 60 * 1000

        return down
          ? time - time % roundDownTime
          : time + (roundDownTime - (time % roundDownTime))
      },
      toTime (tms) {
        return new Date(tms.year, tms.month - 1, tms.day, tms.hour, tms.minute).getTime();
      },
      formatDate(date) {
          let d = new Date(date),
              month = '' + (d.getMonth() + 1),
              day = '' + d.getDate(),
              year = d.getFullYear();

          if (month.length < 2)
              month = '0' + month;
          if (day.length < 2)
              day = '0' + day;

          return [year, month, day].join('-');
      },
      formatTime(date) {
          let d = new Date(date);
          let hh = d.getHours();
          if (hh < 10) hh = '0' + hh;
          let mm = d.getMinutes();
          if (mm < 10) mm = '0' + mm;

          return [hh, mm].join(':');

      },
    },
    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Нове заняття' : 'Редагування заняття'
      },
      // получение состояния тасков
      lessons () {
        return this.$store.getters.lessons;
      },
      collector () {
        return {
          id: this.editedItem.id,
          name: this.getName,
          start: this.editedItem.start,
          end: this.editedItem.end,
          students: JSON.stringify(this.editedItem.students),
          price_student: this.editedItem.price_student,
          price_tutor: this.editedItem.price_tutor,
          tutor_id: this.editedItem.tutor_id.id,
          classroom_id: this.editedItem.classroom_id,
          period_end: this.getLastdate,
          comment: this.editedItem.comment,
          color: this.getColor,
        };
      },
      getName () {
        let nm = '';
        this.editedItem.students.forEach(function(el) {
          nm = nm + el.lname + ' ';
        });
        return this.editedItem.students[0].class + ' ' + nm;
      },
      getColor () {
        let cl = this.editedItem.students[0].class;
        if (cl === 1) return 'light-blue';
        if (cl === 2) return 'cyan';
        if (cl === 3) return 'teal';
        if (cl === 4) return 'green';
        if (cl === 5) return 'light-green';
        if (cl === 6) return 'lime darken-1';
        if (cl === 7) return 'amber';
        if (cl === 8) return 'orange';
        if (cl === 9) return 'deep-orange';
        if (cl === 10) return 'red';
        if (cl === 11) return 'pink';
        else return 'blue-grey';
      },
      getLastdate () {
        if (this.datePic.last) {
          return this.datePic.last + ' ' + this.datePic.end;
        }
      },
      getTutorbyId () {
        let tutor = this.tutors.find(element => element.id === this.selectedEvent.tutor_id);
        if (tutor) {
          return tutor.lname +' '+ tutor.name +' '+ tutor.mname;
        }
      },
      getClassbyId () {
        let classroom = this.classrooms.find(element => element.id === this.selectedEvent.classroom_id);
        if (classroom) {
          return classroom.name;
        }
      }
    },
  }
</script>
