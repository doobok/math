<template>
  <div>
    <v-card>
      <v-card-title>
        Користувачі TutorMath
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
        <template v-slot:item.actions="{ item }">
          <v-icon
            class="mr-2"
            @click="delItem(item)"
          >
            mdi-delete
          </v-icon>
        </template>
      </v-data-table>
    </v-card>

</div>
</template>

<script>
  export default {
    data () {
      return {
        search: '',
        headers: [
          {
            text: 'ID',
            align: 'start',
            value: 'id',
          },
          { text: 'Нікнейм', value: 'name' },
          { text: 'Реальне Імʼя', value: 'realname' },
          { text: 'Роль', value: 'role' },
          { text: 'Роль ID', value: 'role_id' },
          { text: 'Зареєстрований', value: 'created_at' },
          { text: 'Дії', value: 'actions', sortable: false },
        ],
        items: [],
    }
  },
  mounted () {
    this.getData();
  },
  methods: {
      getData () {
        axios
            .get('/api/v1/users-get')
            .then(response => {
              this.items = response.data;
            });
      },

      delItem(item) {
        if (confirm('Ви дійсно бажаєте видалити користувача ' + item.realname + '? Після підтвердження він буде видалений із системи назавжди')) {
          axios
            .delete('/api/v1/user-del/' + item.id)
            .then(response => {
              if (response.data.success === true) {
                let index = this.items.findIndex(elm => elm.id === item.id);
                this.items.splice(index, 1);
              }
            })
            .catch(err => {
              let e = { ...err    }
              console.log(e);
              alert('Виникла помилка, повторіть спробу трішки пізніше');
            });
        }
      },
    },
}
</script>
