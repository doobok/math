<template>
  <div class="uk-text-center uk-padding-small">
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
      <div class="uk-padding-small">
        <img data-src="/telegram.png" uk-img>
        <p class="uk-h3">{{$ml.get('phone_sended')}}</p>
        <p class="uk-text-meta">{{$ml.get('phone_sended_desc')}}</p>
      </div>
    </template>
    <div v-show="formshow">
      <p>{{$ml.get('input_phone_desc')}}</p>
      <div class="uk-margin">
          <input v-model="phone"
          ref="phone"
          class="uk-input uk-form-large uk-width-medium"
          type="text"
          placeholder="+38"
          @blur="$v.phone.$touch()">
      </div>
      <div class="uk-margin" v-if="$v.phone.$error">
        <transition name="fade">
          <p class="uk-text-small uk-text-danger">{{$ml.get('pls_correct_phone')}}</p>
        </transition>
      </div>
      <div class="uk-margin">
        <button
        :disabled="$v.$invalid"
        class="uk-button uk-text-truncate uk-button-large uk-button-danger uk-width-medium"
        type="button"
        @click="send()">
        <template v-if="$v.$invalid">
          {{$ml.get('enter_phone')}}
        </template>
        <template v-else>
          {{$ml.get('send')}}
        </template>
      </button>
      </div>
      <p class="uk-text-small uk-text-muted">*{{$ml.get('trust')}}</p>

    </div>
  </div>
</template>

<script>
import Inputmask from 'inputmask';
import { required } from "vuelidate/lib/validators";

export default {
  props: ['lang'],
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
    created: function(){
          this.$ml.change(this.lang);
      },
    validations: {
        phone: {
          required,
          validFormat: val => /^\+38 \(0\d{2}\) \d{3}\-\d{4}$/.test(val),
        },
      },
}


  </script>
