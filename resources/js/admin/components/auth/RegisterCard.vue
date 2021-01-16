<template>
  <v-container>
      <v-row no-gutters style="height: 730px;">
        <v-col align-self="center">

          <validation-observer ref="observer" v-slot="{ invalid }">

          <v-card
            class="mx-auto elevation-5"
            max-width="580"
          >
          <v-card-title>Реєстрація учасника</v-card-title>
            <v-card-text>
              <v-col cols="11">
                <v-alert border="right" color="light-green darken-1" dark>
                  Якщо ви бачите це повідомлення, це означає, що ви отримали запрошення на реєстрацію в системі TutorMath. <br>Щоб розпочати роботу пройдіть коротку реєстрацію...
                </v-alert>
              </v-col>

              <v-col cols="11">
                <v-alert border="right" color="green lighten-5">

                <validation-provider :rules="{required: true, alpha_num: true, min: 4}" v-slot="{ errors }">
                  <v-text-field
                    v-model="name"
                    label="Придумайте унікальний логін"
                    prepend-inner-icon="mdi-account"
                    :error-messages="errors"
                    hint="Для уникнення конфліктів використовуйте латиницю"
                    name="name"
                  ></v-text-field>
                </validation-provider>

                <validation-provider :rules="{required: true, min: 6, regex: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*()?//|]{6,}$/}" v-slot="{ errors }">
                  <v-text-field
                    v-model="password"
                    :append-icon="showpass ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="showpass ? 'text' : 'password'"
                    name="password"
                    :error-messages="errors"
                    label="Придумайте надійний пароль"
                    counter
                    hint="Ведіть щонайменше 6 символів"
                    @click:append="showpass = !showpass"
                  ></v-text-field>
                </validation-provider>

                <template v-if="!showpass">
                  <validation-provider :rules="{required: true, is: password}" v-slot="{ errors }">
                    <v-text-field
                      v-model="confirm"
                      :error-messages="errors"
                      append-icon="mdi-eye-off"
                      type="password"
                      name="conf"
                      label="Повторіть пароль"
                    ></v-text-field>
                  </validation-provider>
                </template>

                </v-alert>
              </v-col>
              <v-col cols="11">
                <v-alert border="right" color="amber" dark>
                  *Запамʼятайте, або запишіть логін та пароль, вони будуть необхідні для наступного входу, система не передбачає самостійного відновлення пароля, також ваш інвайт буде не дійсним після реєстрації!
                </v-alert>
              </v-col>
              <v-col cols="10">
                <v-btn block depressed color="light-green darken-1 white--text"
                  :disabled="invalid"
                  @click="send"
                  >
                  Зареєструватись
                  <v-icon>mdi-emby</v-icon>
                </v-btn>
              </v-col>
            </v-card-text>
          </v-card>
          </validation-observer>

        </v-col>
      </v-row>
    </v-container>
</template>

<script>
  export default {
    props: ['invite'],
    data: () => ({
      name: '',
      password: '',
      confirm: '',
      showpass: false,
      code: 0,
    }),
    methods: {
      checkName () {
        axios.get('/api/check-name', { params: { name: this.name }} )
            .then(
              response => {
                this.code = response.data.status;
            });
      },
      send () {
        if (this.code === 404) {
          console.log(this.invite);
          axios
            .post('/api/register', { name: this.name, password: this.password, invite: this.invite})
            .then(response => {
              window.location.href = '/login';
            })
            .catch(err => {
              let e = { ...err    }
              // console.log(e);
              alert('Виникла помилка, повторіть спробу трішки пізніше');
            });
        } else {
          alert('Вказаний логін вже використовується, спробуйте вказати інший та повторити спробу');
        }
      }

    },
    watch: {
        name(val) {
          if (val.length > 3) {
            this.checkName();
          }
        },
    },
  }
</script>
