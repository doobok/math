<template>
  <div>
    <v-dialog
      v-if="!theTutor"
      v-model="editForm"
      persistent
      width="800"
      style="overflow-x: hidden"
    >
    <validation-observer ref="observer" v-slot="{ invalid }">
      <v-card>
        <v-card-title class="headline">{{ formTitle }}</v-card-title>

        <v-card-text>

          <v-container>
            <v-row>
              <v-col class="d-flex" cols="10" sm="5">
                <validation-provider rules="required" v-slot="{ errors }">
                  <v-select
                    v-model="editedItem.tutor_id"
                    :items="tutors"
                    :hint="`${editedItem.tutor_id.lname} ${editedItem.tutor_id.name} ${editedItem.tutor_id.mname}`"
                    item-text="lname"
                    label="Тьютор"
                    item-value="id"
                    return-object
                    :error-messages="errors"
                  ></v-select>
                </validation-provider>
              </v-col>
              <v-col class="d-flex" cols="10" sm="5">
                <validation-provider rules="required" v-slot="{ errors }">
                  <v-select
                    v-model="editedItem.classroom_id"
                    :items="classrooms"
                    item-text="name"
                    item-value="id"
                    label="Кабінет"
                    :error-messages="errors"
                  ></v-select>
                </validation-provider>
              </v-col>
              <v-col class="d-flex" cols="8" sm="4">
                <validation-provider :rules="{required: true, integer: true}" v-slot="{ errors }">
                  <v-text-field
                    v-model="editedItem.price_student"
                    name="studentprice"
                    prepend-icon="mdi-cash-multiple"
                    label="Вартість для учня"
                    :error-messages="errors"
                  ></v-text-field>
                </validation-provider>
              </v-col>
              <v-col class="d-flex" cols="8" sm="4"
              :class="activeTutorprice"
              >
                <validation-provider rules="integer" v-slot="{ errors }">
                  <v-text-field
                    v-model="editedItem.price_tutor"
                    name="tutorprice"
                    prepend-icon="mdi-cash-multiple"
                    label="Комісія тьютора"
                    :error-messages="errors"
                  ></v-text-field>
                </validation-provider>
              </v-col>
              <v-col class="d-flex" cols="2"
                :class="profitStyle"
              >
                <v-text-field
                  prepend-icon="mdi-cash-multiple"
                  :value="profitCash"
                  label="Профіт"
                  disabled
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
                      <validation-provider rules="required" v-slot="{ errors }">
                        <v-text-field
                          v-model="datePic.date"
                          label="Дата"
                          prepend-icon="mdi-calendar"
                          readonly
                          v-bind="attrs"
                          v-on="on"
                          :error-messages="errors"
                        ></v-text-field>
                      </validation-provider>
                    </template>
                    <v-date-picker
                      v-model="datePic.date"
                      locale="uk"
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
                      <validation-provider rules="required" v-slot="{ errors }">
                        <v-text-field
                          v-model="datePic.start"
                          label="Час початку"
                          prepend-icon="mdi-clock-time-four-outline"
                          readonly
                          v-bind="attrs"
                          v-on="on"
                          :error-messages="errors"
                        ></v-text-field>
                      </validation-provider>
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
                      <validation-provider rules="required" v-slot="{ errors }">
                        <v-text-field
                          v-model="datePic.end"
                          label="Час закінчення"
                          prepend-icon="mdi-clock-time-four-outline"
                          readonly
                          v-bind="attrs"
                          v-on="on"
                          :error-messages="errors"
                        ></v-text-field>
                      </validation-provider>
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
                <v-col class="d-flex" cols="10" sm="7">
                  <validation-provider rules="required" v-slot="{ errors }">
                    <v-select
                      v-model="editedItem.students"
                      :items="students"
                      label="Учні"
                      item-text="concname"
                      item-value="id"
                      item-color="green"
                      chips
                      deletable-chips
                      multiple
                      return-object
                      :error-messages="errors"
                    ></v-select>
                  </validation-provider>
                </v-col>
                <v-col class="d-flex" cols="10" sm="3">
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
                          label="Випуск"
                          prepend-icon="mdi-calendar"
                          readonly
                          v-bind="attrs"
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker
                        v-model="datePic.last"
                        locale="uk"
                        @input="lastDatePic = false"
                      ></v-date-picker>
                    </v-menu>
                  </v-col>
                <template v-if="editedItem.students">
                  <v-col class="d-flex" cols="10" sm="7">
                    <v-select
                      v-model="editedItem.pass"
                      :items="editedItem.students"
                      label="Відсутні"
                      item-text="concname"
                      item-value="id"
                      chips
                      deletable-chips
                      item-color="red"
                      multiple
                      return-object
                    ></v-select>
                  </v-col>
                  <v-col class="d-flex" ols="10" sm="3">
                    <v-switch
                      v-model="editedItem.pass_paid"
                      :label="passPaid"
                    ></v-switch>
                  </v-col>
                </template>
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
          <v-btn color="blue darken-1" text @click="save" :disabled="invalid" v-if="!editedItem.computed">
            зберегти
          </v-btn>
        </v-card-actions>
      </v-card>
    </validation-observer>
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

          <v-col class="d-flex" cols="2">
            <v-select
              @change="getLessons"
              v-model="filter.tutor"
              :items="tutors"
              item-text="lname"
              item-value="id"
              label="Тьютор"
            ></v-select>
          </v-col>
          <v-col class="d-flex" cols="2">
            <v-select
              @change="getLessons"
              v-model="filter.classroom"
              :items="classrooms"
              item-text="name"
              item-value="id"
              label="Кабінет"
            ></v-select>
          </v-col>
          <v-btn elevation="1" icon @click="resetFilter">
            <v-icon color="light-green lighten-1">
              mdi-restart
            </v-icon>
          </v-btn>

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
      <v-sheet height="750">
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
              <v-btn
                v-if="!theTutor"
                @click="edit" icon>
                <v-icon>mdi-pencil</v-icon>
              </v-btn>
              <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
              <v-spacer></v-spacer>
              <template v-if="!theTutor">
                <v-menu v-if="!selectedEvent.computed" bottom left>
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      dark
                      icon
                      v-bind="attrs"
                      v-on="on"
                    >
                      <v-icon>mdi-dots-vertical</v-icon>
                    </v-btn>
                  </template>

                  <v-list flat>
                    <v-list-item-group
                      color="primary"
                    >
                    <v-list-item @click="copyLesson">
                      <v-list-item-title>
                        <v-icon>mdi-content-copy</v-icon>
                          Дублювати
                      </v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="delLesson">
                      <v-icon>mdi-delete</v-icon>
                        <v-list-item-title>Видалити</v-list-item-title>
                    </v-list-item>
                    </v-list-item-group>
                  </v-list>
                </v-menu>
              </template>
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

      </v-sheet>
    </v-col>
  </v-row>
</div>
</template>

<script>
  export default {
    props: ['user'],
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
        pass: '',
        pass_paid: true,
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
        pass: '',
        pass_paid: true,
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
      filter: {
        tutor: 'all',
        classroom: 'all',
        start: '',
        end: '',
      },
      tmp: {
        start: '',
        end: '',
      }
    }),
    mounted () {
      axios
        .get('/api/v1/lesson-start-data')
        .then(response => {
          this.students = response.data.students;
          this.tutors = response.data.tutors;
          this.classrooms = response.data.classrooms;
        })
        .catch(err => {
          let e = { ...err    }
          console.log(e);
          alert('Виникла помилка, повторіть спробу трішки пізніше');
        });

      this.$refs.calendar.checkChange();
    },
    methods: {
      getLessons () {
        this.$store.dispatch('GET_LESSONS', {params: this.filter});
      },
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
        this.editedIndex =  this.lessons.indexOf(this.selectedEvent);

        this.editForm = true;
        this.editedItem = this.selectedEvent;
        this.datePic.date = this.formatDate(this.selectedEvent.start);
        this.datePic.start = this.formatTime(this.selectedEvent.start);
        this.datePic.end = this.formatTime(this.selectedEvent.end);
        if (this.selectedEvent.period_end) {
          this.datePic.last = this.formatDate(this.selectedEvent.period_end);
        }
        if (this.selectedEvent.students.constructor != Array) {
          this.editedItem.students = JSON.parse(this.selectedEvent.students);
        }
        if (this.selectedEvent.pass && this.selectedEvent.pass.constructor != Array) {
          this.editedItem.pass = JSON.parse(this.selectedEvent.pass);
        }
      },
      // сохранить форму
      save () {
        this.editedItem.start = this.datePic.date + ' ' + this.datePic.start;
        this.editedItem.end = this.datePic.date + ' ' + this.datePic.end;

        if (this.editedIndex > -1) {
            this.$store.dispatch('EDIT_LESSON', this.collector).then((res) => {
              if (res === false) {
                this.selectedEvent.start = this.tmp.start
                this.selectedEvent.end = this.tmp.end
              }
          });
        } else {
          this.$store.dispatch('SET_LESSON', this.collector);
        }
        this.close()
      },
      copyLesson () {
        this.$store.dispatch('COPY_LESSON', this.selectedEvent.id);
        this.selectedOpen = false;
      },
      delLesson () {
        if (confirm("Ви дійсно бажаєте видалити це заняття?")) {
          this.$store.dispatch('DEL_LESSON', this.selectedEvent.id);
          this.selectedOpen = false;
        }
      },
      viewDay ({ date }) {
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
        // console.log('Клик по событию');//норм
          const open = () => {
            this.selectedEvent = event
            this.selectedElement = nativeEvent.target
            setTimeout(() => {
              this.selectedOpen = true
            }, 10)
          }

        if (this.selectedOpen) {
          this.selectedOpen = false
          setTimeout(open, 10)
        } else {
          open()
        }

        nativeEvent.stopPropagation()
      },
      updateRange ({ start, end }) {
        this.filter.start = start.date + ' 00:00:00';
        this.filter.end = end.date + ' 23:59:59';
        this.getLessons();
      },
      startDrag ({ event, timed }) {
        // не доступно для тьютора
        if (!this.theTutor) {
          // console.log('Начало перетаскивания');
          if (event && timed) {
            this.dragEvent = event
            // временое хранение времени при перемещении
            this.tmp.start = event.start
            this.tmp.end = event.end

            this.dragEvent.start = new Date(event.start)
            this.dragEvent.end = new Date(event.end)
            this.dragTime = null
            this.extendOriginal = null
          }
        }
      },
      startTime (tms) {
        // console.log('Мышка опустилась на шкалу времени');
        const mouse = this.toTime(tms)

        if (this.dragEvent && this.dragTime === null) {
          const start = this.dragEvent.start

          this.dragTime = mouse - start
        } else {
          this.createStart = this.roundTime(mouse)
          this.editForm = true;
          this.datePic.date = this.formatDate(this.createStart);
          this.datePic.start = this.formatTime(this.createStart);
          this.datePic.end = this.formatTime(this.createStart+3000000);
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
        // console.log('Конец перемещения');
        if (this.dragEvent && this.dragTime !== null) {
          // отсекаем возможность переноса занятий в прошлое и редактирования уже обработаных
          if (this.deadline > this.dragEvent.start || this.dragEvent.computed) {
            // console.log('last time, don`t work');
            this.dragEvent.start = this.tmp.start
            this.dragEvent.end = this.tmp.end
          } else {
            // работаем с будущим временем
            if (Date.parse(this.tmp.start) != Date.parse(this.dragEvent.start)) {
              this.$store.dispatch('EDIT_TIME', { 'id': this.dragEvent.id, 'start': this.dragEvent.start/1000, 'end': this.dragEvent.end/1000 });
              this.selectedOpen = false;
            }
          }
        }
        this.dragTime = null
        this.dragEvent = null
        this.createEvent = null
        this.createStart = null
        this.extendOriginal = null
      },
      cancelDrag () {
        if (this.createEvent) {
          if (this.extendOriginal) {
            this.createEvent.end = this.extendOriginal
          } else {
            const i = this.lessons.indexOf(this.createEvent)
            if (i !== -1) {
              this.lessons.splice(i, 1)
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
      resetFilter() {
        this.filter.tutor = 'all';
        this.filter.classroom = 'all';
        this.getLessons()
      },
    },
    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Нове заняття' : 'Редагування заняття'
      },
      passPaid () {
        return this.editedItem.pass_paid ? 'Оплачуваний пропуск' : 'Не оплачуваний пропуск'
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
          timed: true,
          pass: this.getPassStudent,
          pass_paid: this.editedItem.pass_paid,
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
        let color;
        switch (cl) {
          case 1: color = 'light-blue'; break;
          case 2: color = 'cyan'; break;
          case 3: color = 'teal'; break;
          case 4: color = 'green'; break;
          case 5: color = 'light-green'; break;
          case 6: color = 'lime'; break;
          case 7: color = 'amber'; break;
          case 8: color = 'orange'; break;
          case 9: color = 'deep-orange'; break;
          case 10: color = 'red'; break;
          case 11: color = 'pink'; break;
          default:
            color = 'blue-grey';
        }
        if (this.editedItem.pass) {
          if (this.editedItem.students.length == this.editedItem.pass.length) {
            color = color + ' lighten-4';
          }
        }
        return color;
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
      },
      getPassStudent () {
        if (this.editedItem.pass) {
          return JSON.stringify(this.editedItem.pass);
        }
      },
      deadline: function() {
        let seconds = Date.now() / 1000; // Количество секунд до настоящего момента
        seconds -= seconds % 86400; // Отсечь секунды после полуночи нулевого меридиана
        let date = seconds * 1000;

        return date
      },
      theTutor () {
        return this.user.role === 'tutor';
      },
      profitCash () {
        let cnt;
        let sum;
        let dif = 0;
        let deficiency = 0;
        // якщо заняття оплачуються
        if (this.editedItem.pass_paid) {

          sum = this.editedItem.students.length * this.editedItem.price_student - this.editedItem.price_tutor

        // якщо не оплачуються
        } else {

          if (this.editedItem.pass) {
            cnt = this.editedItem.students.length - this.editedItem.pass.length;
          } else {
            cnt = this.editedItem.students.length;
          }
          sum = cnt * this.editedItem.price_student - this.editedItem.price_tutor;

        }
        return sum;
      },
      profitStyle () {
        if (this.profitCash > 0) {
          return 'rounded-pill green lighten-4';
        } else if (this.profitCash < 0) {
          return 'rounded-pill red lighten-4';
        }
      },
      activeTutorprice () {
        if (this.editedItem.pass) {
          if (this.editedItem.pass.length > 0 && this.editedItem.price_tutor) {
            return 'rounded-pill amber lighten-4';
          }
        }
      }
    },
  }
</script>
