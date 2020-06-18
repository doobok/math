<template>
  <div class="uk-text-center">
    <div v-if="errorshow" class="uk-alert-danger" uk-alert>
        <p>{{error}}</p>
    </div>
    <template v-if="loading" id="loading">
      <div class="bubblingG">
      	<span id="bubblingG_1">
      	</span>
      	<span id="bubblingG_2">
      	</span>
      	<span id="bubblingG_3">
      	</span>
      </div>
    </template>
    <template v-if="subformshow">
      <div class="uk-tile-muted uk-padding-small">
        <p class="uk-h3">Номер телефона отправлен</p>
        <p class="uk-text-meta">Мы получили номер Вашего телефона. Пожалуйста, оставайтесь на связи. Наш менеджер обычно звонит в течение 3-5 мин.</p>
      </div>
    </template>
    <div v-show="formshow">
      <p>Оставте, пожалуйста, контактный номер телефона и уже через минуту наш менеджер свяжется с Вами</p>
      <div class="uk-margin">
          <input v-model="phone"
          ref="phone"
          class="uk-input uk-form-width-medium uk-form-large uk-form-width-large"
          type="text"
          placeholder="+38"
          @blur="$v.phone.$touch()">
      </div>
      <div class="uk-margin" v-if="$v.phone.$error">
        <transition name="fade">
          <p class="uk-text-small uk-text-danger">Пожалуйста, введите действительный номер телефона</p>
        </transition>
      </div>
      <div class="uk-margin">
        <button
        :disabled="$v.$invalid"
        class="uk-button uk-button-danger uk-button-large uk-form-width-large"
        type="button"
        @click="send()">
        <template v-if="$v.$invalid">
          Введите номер телефона
        </template>
        <template v-else>
          Отправить
        </template>
      </button>
      </div>
      <p class="uk-text-small uk-text-muted">* можете не волноваться, ваш телефон не будет передан третьим лицам</p>

    </div>
  </div>
</template>

<script>
import Inputmask from 'inputmask';
import { required } from "vuelidate/lib/validators";

export default {
  // props: ['sourceid', 'button_title', 'redirect_uri'],
  data: function() {
      return {
        loading: false,
        formshow: true,
        subformshow: false,
        errorshow: false,
        error: '',
        phone: ''
      }
    },
    methods: {
      send() {
        this.formshow = false;
        this.loading = true;

        this.$store.dispatch('SEND_LEAD', { phone: this.phoneNum, slug: this.getSlug }).then((res) => {
          // проверяем наличие служебного сообщения из сервера
          if (res.msg) {
            this.loading = false;
            this.errorshow = true;
            this.error = res.msg;
            // console.log(res);

          // проверяем облаботал ли сервер запрос
          } else if (res.success) {
            this.loading = false;
            this.subformshow = true;
            // console.log(res);

            // вызываем событие GA
            // gtag('event', 'sendPhone', {'event_category': 'getPhone', 'event_label': this.button_title }); return true;

          // в противном случае показываем сообщение об ошибке
          } else {
            this.loading = false;
            this.formshow = true;
            this.errorshow = true;
            this.error = 'Возникла ошибка. Данные не удалось отправить. Попробуйте повторить попытку немного позже.';
            // console.log(res);
          }
        })

      },
      nextPage() {
        window.location.href= this.redirect_uri;

      }
    },
    computed: {
      getSlug: function() {
          return this.$store.getters.SLUG;
        },
      phoneNum: function() {
                var str = this.phone;
                str = str.replace(/[^0-9.]/g, '');
                str = str.substr(2);
                return str;
            }
    },
    mounted() {
      let im = new Inputmask('+38 '+'(999) 999-9999');
            im.mask(this.$refs.phone);

      },
      validations: {
              phone: {
                required,
                validFormat: val => /^\+38 \(0\d{2}\) \d{3}\-\d{4}$/.test(val),
              },
            },
}


  </script>
