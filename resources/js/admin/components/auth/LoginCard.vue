<template>
  <div class="">
    <div class="uk-text-center uk-margin">
      <span class="uk-h3 uk-margin-bottom">Привіт!</span><br>
      <span class="uk-text-small">для продовження введи свої логін та пароль вказані при реєстрації</span>
    </div>
   <form class="toggle-class" @submit.prevent="tryGet">

    <fieldset class="uk-fieldset">
      <div class="uk-margin-small">
        <div class="uk-inline uk-width-1-1">
          <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: user"></span>
          <input class="uk-input uk-border-pill" v-model="username" placeholder="Логін" id="username" type="text" name="username" required autocomplete="username" autofocus>
        </div>
      </div>
      <div class="uk-margin-small">
        <div class="uk-inline uk-width-1-1">
          <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: lock"></span>
          <input class="uk-input uk-border-pill" v-model="password" placeholder="Пароль" id="password" type="password" name="password" required>
        </div>
      </div>
      <div class="uk-margin-small uk-text-center">
        <label><input class="uk-checkbox" v-model="remember" type="checkbox"> Запамʼятати мене</label>
      </div>
      <div v-if="err">
          <p class="uk-text-small uk-text-danger">{{err}}</p>
      </div>

      <div class="uk-margin-bottom uk-margin-top">
        <button type="submit" class="uk-button uk-button-primary uk-border-pill uk-width-1-1">Увійти</button>
      </div>
    </fieldset>
  </form>
  </div>


</template>


<script>
  export default {
    props: ['csrf'],
    data: () => ({
      username: '',
      password: '',
      remember: false,
      err: '',

    }),
    methods: {
      tryGet () {
        axios
          .post('/login', { name: this.username, password: this.password, remember: this.remember})
          .then(response => {
            if (response.data.status === 406) {
              this.err = response.data.msg;
            } else if (response.data.status === 201) {
              window.location.href = response.data.url;
            }
          })
          .catch(err => {
            let e = { ...err    }
            // console.log(e);
            alert('Виникла непередбачена помилка, повторіть спробу трішки пізніше');
          });
      }
    }


  }
</script>
