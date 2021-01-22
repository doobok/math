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
      :sort-by="['active', 'balance']"
      :sort-desc="[true, false]"
      >
      <template v-slot:item.balance="{ item }">
        <span :class="getColor(item.balance)">
          {{ item.balance }}
        </span>
      </template>
      <template v-slot:item.active="{ item }">
        <v-checkbox
          v-model="item.active"
          disabled
        ></v-checkbox>
      </template>
      <template v-slot:item.refill="{ item }">
        <v-btn outlined x-small color="green" @click="showRefill(item)">
          поповнити
        </v-btn>
      </template>
      <template v-slot:item.actions="{ item }">
      <v-icon
        class="mr-2"
        @click="editItem(item)"
      >
        mdi-pencil
      </v-icon>
      <v-icon
        class="mr-2"
        @click="openItem(item)"
      >
        mdi-account
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
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                  <validation-provider rules="required" v-slot="{ errors }">
                    <v-text-field
                      v-model="editedItem.lname"
                      :error-messages="errors"
                      label="Прізвище"
                    ></v-text-field>
                  </validation-provider>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                  <validation-provider rules="required" v-slot="{ errors }">
                    <v-text-field
                      v-model="editedItem.name"
                      :error-messages="errors"
                      label="Імʼя"
                    ></v-text-field>
                  </validation-provider>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                  <validation-provider rules="required" v-slot="{ errors }">
                    <v-select
                    v-model="editedItem.class"
                      :items="clases"
                      :error-messages="errors"
                      label="Клас"
                    ></v-select>
                  </validation-provider>
                  </v-col>
                  <v-col cols="10">
                    <v-text-field
                      v-model="editedItem.phone"
                      label="Номер телефону"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="10">
                    <v-text-field
                      v-model="editedItem.comment"
                      label="Коментар"
                    ></v-text-field>
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
        <v-dialog
          v-model="refillDialog"
          max-width="310px"
        >
        <validation-observer ref="observer" v-slot="{ invalid }">
        <v-card>
          <v-card-title v-if="refillUser">
            <span class="headline">{{ refillTitle}}</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="7">
                  <validation-provider :rules="{required: true, integer: true}" v-slot="{ errors }">
                    <v-text-field
                      v-model="refillSum"
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
              @click="closeRefill"
            >
              Скасувати
            </v-btn>
            <v-btn
              color="blue darken-1"
              text
              :disabled="invalid"
              @click="setRefill()"
            >
              поповнити
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
        clases: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11,],
        headers: [
          { text: 'ID', value: 'id', align: 'start' },
          {
            text: 'Прізвище',
            value: 'lname',
          },
          { text: 'Імʼя', value: 'name' },
          { text: 'Клас', value: 'class' },
          { text: 'Телефон', value: 'phone' },
          { text: 'Баланс', value: 'balance' },
          { text: '', value: 'refill' },
          { text: 'Коментар', value: 'comment' },
          { text: 'Активність', value: 'active' },
          { text: 'Дії', value: 'actions', sortable: false },
        ],
        items: [],
        editedIndex: -1,
        editedItem: {
          id: '',
          name: '',
          lname: '',
          class: '',
          phone: '',
          comment: '',
          active: true,
        },
        defaultItem: {
          id: '',
          name: '',
          lname: '',
          class: '',
          phone: '',
          comment: '',
          active: true,
        },
        refillSum: '',
        refillDialog: '',
        refillUser: '',
    }
  },
  mounted () {
    axios
          .get('/api/v1/student-get')
          .then(response => {
              this.items = response.data;
          });
  },
  computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Новий учень' : 'Редагування учня'
      },
      refillTitle () {
        return 'Поповнити баланс учня ' + this.refillUser.concname;
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
        .patch('/api/v1/student-upd/' + this.editedItem.id, this.editedItem)
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
            .post('/api/v1/student-set', this.editedItem)
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
    openItem(item) {
      document.location.href = '/students/' + item.id;
    },
    getColor (balance) {
      if (balance > 400) return 'green--text'
      else if (balance > 0) return 'orange--text'
      else return 'red--text'
    },
    showRefill(user) {
      this.refillUser = user;
      this.refillDialog = true;
    },
    closeRefill() {
      this.refillDialog = false;
      this.refillUser = '';
      this.refillSum = '';
    },
    setRefill() {
      axios
        .post('/api/v1/refill-student', {sum: this.refillSum, id: this.refillUser.id})
        .then(response => {
          if (response.data.success === true) {
            let indx = this.items.indexOf(this.refillUser);
            let balance = this.items[indx].balance;
            this.items[indx].balance = +balance + +this.refillSum;
            this.closeRefill();
          }
        })
        .catch(err => {
          let e = { ...err    }
          console.log(e);
          alert('Виникла помилка, повторіть спробу трішки пізніше');
        });

    }
  }
}
</script>
