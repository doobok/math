<template>
  <v-container>
    <v-alert
      class="py-5"
      color="orange"
      dark
      dense
      icon="mdi-school"
      prominent
      v-if="rooms.length < 1"
    >
      Сьогодні у тебе немає жодного онлайн заняття, або усі вони вже відбулися. Будь-ласка, повертайся пізніше!
    </v-alert>
    <v-row>
      <template v-for="room in rooms">
        <v-card
          class="mx-left ma-1"
          :color="room.color"
          dark
          max-width="400"
        >
          <v-card-title>
            <v-icon large left>mdi-bell-ring</v-icon>
            <span class="title font-weight-regular">Онлайн заняття</span>
          </v-card-title>

          <v-card-text>
            <p class="title">{{room.start}}
              <v-icon medium>mdi-clock</v-icon>
            </p>
            <span class="headline font-weight-bold">{{room.name}}</span>

          </v-card-text>

          <v-card-actions>
            <v-list-item class="grow">
                <v-icon large left>mdi-account-circle</v-icon>

              <v-list-item-content>
                <v-list-item-title>{{getTutorbyId(room.tutor_id)}}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-card-actions>
          <v-btn class="ma-2 text-center" outlined color="white" min-width="300"
            :href="`/online/`+ room.id"
            >
              Перейти в кімнату <v-icon right dark>mdi-forward</v-icon>
          </v-btn>
        </v-card>
      </template>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      rooms: [],
      tutors: [],

    }
  },

  mounted() {
    this.getRooms();
    this.getStartData();
  },
  methods: {
    getRooms() {
      axios
          .get('/api/v1/online-rooms-get')
          .then(response => {
            this.rooms = response.data;
          });
    },
    getStartData() {
        axios
          .get('/api/v1/online-start-data')
          .then(response => {
              this.tutors = response.data.tutors;
          });
    },
    getTutorbyId(id) {
      let tutor = this.tutors.find(element => element.id === id);
      if (tutor) {
        return tutor.lname +' '+ tutor.name +' '+ tutor.mname;
      }
    },
  }
};
</script>
