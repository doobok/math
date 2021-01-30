<template>
  <v-card
    class="mx-auto"
    outlined
  >
    <v-list-item three-line>
      <v-list-item-content>
        <v-list-item-title class="headline mb-1">
          Стан телеграм бота
        </v-list-item-title>
        <v-list-item-subtitle>
          <template v-if="status == 1">
            Все впорядку Ваш бот працює
          </template>
          <template v-else>
            Вебхук не задано, або система про нього незнає, виконайте перевірку і при необхідності згенеруйте новий
          </template>

        </v-list-item-subtitle>
      </v-list-item-content>

      <v-list-item-avatar
        tile
        size="80"
        :color="statusColor"
      >
      <v-icon
      x-large
      color="white"
    >
      mdi-robot-outline
    </v-icon>
    </v-list-item-avatar>
    </v-list-item>
    <v-alert
      v-if="info"
      class="ma-3"
      border="top"
      colored-border
      :color="statusColor"
      type="info"
      elevation="2"
    >
      {{info}}
    </v-alert>

    <v-card-actions>
      <v-btn outlined color="blue" @click="getWebhook">
        Перевірити статус
      </v-btn>
      <template v-if="status == 1">
        <v-btn outlined color="red" @click="delWebhook">
          Видалити вебхук
        </v-btn>
      </template>
      <template v-else>
        <v-btn outlined color="green" @click="setWebhook">
          Згенерувати вебхук
        </v-btn>
      </template>

    </v-card-actions>

  </v-card>
</template>

<script>
  export default {
    data () {
      return {
        hook: '',
        status: '',
        info: '',
      }
    },
    mounted () {
      axios
            .get('/api/v1/telegram-status-get')
            .then(response => {
                this.status = response.data.status;
            });
    },
    methods: {
      setWebhook () {
        axios
          .get('/api/v1/telegram-set-webhook')
          .then(response => {
            if (response.data.success === true) {
              this.status = 1;
              this.info = 'Вебхук успішно відправлено';
            }
          })
          .catch(err => {
            let e = { ...err    }
            console.log(e);
            alert('Виникла помилка, повторіть спробу трішки пізніше');
          });
      },
      delWebhook () {
        if (confirm('Дійсно бажаєте видалити вебхук? Після підтвердження бот перестане обробляти запити.')) {
          axios
            .get('/api/v1/telegram-del-webhook')
            .then(response => {
              if (response.data.success === true) {
                this.status = 0;
                this.info = 'Вебхук видалено';
              }
            })
            .catch(err => {
              let e = { ...err    }
              console.log(e);
              alert('Виникла помилка, повторіть спробу трішки пізніше');
            });
        }
      },
      getWebhook () {
        axios
          .get('/api/v1/telegram-get-webhook')
          .then(response => {
            if (response.data != '') {
              this.info = 'Вебхук знайдено';
              this.status = 1;
            } else {
              this.info = 'Вебхук не знайдено';
              this.status = 0;
            }
          })
          .catch(err => {
            let e = { ...err    }
            console.log(e);
            alert('Виникла помилка, повторіть спробу трішки пізніше');
          });
      }
    },
    computed: {
      statusColor () {
        if (this.status == 1) {
          return 'green';
        } else {
          return 'red';
        }
      },
    }
  }
</script>
