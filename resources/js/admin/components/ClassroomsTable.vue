<template>
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
      <template v-slot:item.active="{ item }">
        <v-checkbox
          v-model="item.active"
          disabled
        ></v-checkbox>
      </template>
      <template v-slot:item.actions="{ item }">
      <v-icon
        class="mr-2"
        @click="editItem(item)"
      >
        mdi-pencil
      </v-icon>
    </template>

    <template v-slot:footer>
      <v-toolbar
        flat
      >

        <v-spacer></v-spacer>
        <v-dialog
          v-model="dialog"
          max-width="500px"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              color="green"
              dark
              class="mb-2"
              v-bind="attrs"
              v-on="on">
              Додати
            </v-btn>
          </template>
          <validation-observer ref="observer" v-slot="{ invalid }">
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col cols="10">
                    <validation-provider rules="required" v-slot="{ errors }">
                      <v-text-field
                        v-model="editedItem.name"
                        label="Назва"
                        :error-messages="errors"
                      ></v-text-field>
                    </validation-provider>
                  </v-col>
                  <v-col cols="10">
                    <validation-provider rules="required" v-slot="{ errors }">
                      <v-text-field
                        v-model="editedItem.desc"
                        label="Опис"
                        :error-messages="errors"
                      ></v-text-field>
                    </validation-provider>
                  </v-col>
                  <v-col cols="10">
                    <v-checkbox
                      v-model="editedItem.active"
                      label="Активний"
                    ></v-checkbox>
                  </v-col>

                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                color="red darken-1"
                text
                @click="close"
              >
                Скасувати
              </v-btn>
              <v-btn
                color="blue darken-1"
                text
                :disabled="invalid"
                @click="save"
              >
                зберегти
              </v-btn>
            </v-card-actions>
          </v-card>
          </validation-observer>
        </v-dialog>

      </v-toolbar>
    </template>


    </v-data-table>
  </v-card>
</template>

<script>
  export default {
    data () {
      return {
        dialog: false,
        search: '',
        headers: [
          {
            text: 'Назва',
            align: 'start',
            sortable: false,
            value: 'name',
          },
          { text: 'Опис', value: 'desc' },
          { text: 'Активність', value: 'active' },
          { text: 'Дії', value: 'actions', sortable: false },
        ],
        items: [],
        editedIndex: -1,
        editedItem: {
          id: '',
          name: '',
          desc: '',
          active: true,
        },
        defaultItem: {
          id: '',
          name: '',
          desc: '',
          active: true,
      }
    }
  },
  mounted () {
    axios
          .get('/api/v1/classroom-get')
          .then(response => {
              this.items = response.data;
          });
  },
  computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Новий кабінет' : 'Редагування кабінету'
      },
    },
  methods: {
    editItem (item) {
      this.editedIndex = this.items.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },
    close () {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },
    save () {
      if (this.editedIndex > -1) {
        axios
        .patch('/api/v1/classroom-upd/' + this.editedItem.id, this.editedItem)
            .then(response => {
              if (response.data.success === 'true') {
                // console.log('ok');
              }
            })
            .catch(err => {
              let e = { ...err    }
              console.log(e);
              alert('Виникла помилка, повторіть спробу трішки пізніше');
            });
          Object.assign(this.items[this.editedIndex], this.editedItem)
      } else {
          axios
            .post('/api/v1/classroom-set', this.editedItem)
            .then(response => {
              this.items.push(response.data.data);
            })
            .catch(err => {
              let e = { ...err    }
              console.log(e);
              alert('Виникла помилка, повторіть спробу трішки пізніше');
            });
      }
      this.close()
    },
  }
}
</script>
