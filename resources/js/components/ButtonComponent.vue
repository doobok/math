<template>

  <a href="#modal-full" uk-toggle
  class="uk-button uk-button-danger uk-button-large"
  :class="clases"
  @click="getForm()"
  >{{title}}</a>

</template>

<script>
export default{
  props:['title', 'clases'],
  methods: {
    getForm() {
      // передаем надпись с кнопки в store
      this.$store.dispatch('PUSH_SLUG', this.title);
      // вызываем событие GA
      gtag('event', 'pushButton', {'event_category': 'Phone', 'event_label': this.title }); return true;
    }
  }
}
</script>
