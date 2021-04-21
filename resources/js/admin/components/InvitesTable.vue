<template>
  <div>
    <v-dialog
      v-model="dialog"
      max-width="500px"
    >
    <validation-observer ref="observer" v-slot="{ invalid }">
    <v-card>
      <v-card-title>
        <span class="headline">Видача запрошення</span>
      </v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="10">
              <validation-provider rules="required" v-slot="{ errors }">
                <v-select
                  @change="getUsers"
                  v-model="invite.role"
                  :items="roles"
                  label="Оберіть роль"
                  :error-messages="errors"
                  item-text="name"
                  item-value="slug"
                ></v-select>
              </validation-provider>

              <validation-provider rules="required" v-slot="{ errors }">
                <v-select
                  @change="findUserbyId"
                  v-model="invite.role_id"
                  :items="users"
                  item-text="lname"
                  label="Оберіть користувача"
                  item-value="id"
                  :error-messages="errors"
                ></v-select>
              </validation-provider>

              <v-alert border="right" color="light-green" type="info" dark v-if="invite.name">
                Ви видаєте запрошення для <b>{{invite.name}}</b>
              </v-alert>

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
          @click="inviteSet()"
        >
          Видати запрошення
        </v-btn>
      </v-card-actions>
    </v-card>
    </validation-observer>
    </v-dialog>

    <v-card>
      <v-card-title>
        Запрошення TutorMath
        <v-btn
          class="ml-4"
          elevation="2"
          icon
          @click="dialogOpn"
        ><v-icon>mdi-plus</v-icon>
        </v-btn>
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
        <template v-slot:item.invite="{ item }">
          <span>
            <a>https://bucha.tutor-math.com.ua/register/{{item.invite}}</a>
          </span>
        </template>
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
            text: 'Користувач',
            align: 'start',
            value: 'name',
          },
          { text: 'Роль', value: 'role' },
          { text: 'Invite uri', value: 'invite' },
          { text: 'Згенеровано', value: 'created_at' },
          { text: 'Дії', value: 'actions', sortable: false },
        ],
        items: [],
        dialog: false,
        invite: {
          name: '',
          role: '',
          role_id: '',
          invite: '',
        },
        roles: [
          {slug: 'tutor', name: 'Тьютор'},
          {slug: 'student', name: 'Учень'},
        ],
        users: [],
    }
  },
  mounted () {
    this.getData();
  },
  methods: {
      getData () {
        axios
            .get('/api/v1/invites-get')
            .then(response => {
              this.items = response.data;
            });
      },
      getUsers () {
        axios
            .get('/api/v1/' + this.invite.role + '-get')
            .then(response => {
              this.users = response.data;
            });
            this.invite.role_id = '';
            this.invite.name = '';
      },
      findUserbyId () {
        let user = this.users.find(element => element.id === this.invite.role_id);
        if (user) {
          // назначаэмо імʼя в залежності від ролі
          if (this.invite.role == 'tutor') {
            this.invite.name = user.name +' '+ user.mname;
          } else {
            this.invite.name = user.name;
          }
          // генерируем случайную строку
          let s = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
          this.invite.invite = Array(52).join().split(',').map(function() { return s.charAt(Math.floor(Math.random() * s.length)); }).join('');
        }
      },
      inviteSet() {
        axios
          .post('/api/v1/invite-set', this.invite)
          .then(response => {
            if (response.data.success === true) {
              this.items.unshift(response.data.data);
              this.closeDialog();
            }
          })
          .catch(err => {
            let e = { ...err    }
            console.log(e);
            alert('Виникла помилка, повторіть спробу трішки пізніше');
          });
      },
      closeDialog () {
        this.dialog = false;
        this.invite.name = '';
        this.invite.role = '';
        this.invite.role_id = '';
        this.invite.invite = '';
      },
      dialogOpn () {
        this.dialog = true;
      },
      delItem(item) {
        if (confirm('Ви дійсно бажаєте видалити інвайт для ' + item.name)) {
          axios
            .delete('/api/v1/invite-del/' + item.id)
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
