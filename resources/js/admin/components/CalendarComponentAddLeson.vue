<template>
  <v-menu
      v-if="addOpen"
      justify="center"
      :close-on-content-click="false"
      :activator="this.$refs.calendar"
      offset-x>

      <v-card
        color="grey lighten-4"
        min-width="350px"
        flat>
        <v-toolbar color="green" dark>
          <v-toolbar-title>Додати заняття</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn icon>
            <v-icon>mdi-dots-vertical</v-icon>
          </v-btn>
          </v-toolbar>
        <v-card-text>
          <v-text-field
            v-model="startTime"
            label="E-mail"
            required
          ></v-text-field>
          <span>Text</span>
          <!-- <span v-html="selectedEvent.details"></span> -->
        </v-card-text>
        <v-card-actions>
          <v-btn
            text
            color="secondary"
            @click="addOpen = false">
            Відміна
          </v-btn>
        </v-card-actions>
      </v-card>

  </v-menu>
</template>

<script>
  export default {
    props:['startTime'],

    data: () => ({
      date: '',
    })
  }
</script>
