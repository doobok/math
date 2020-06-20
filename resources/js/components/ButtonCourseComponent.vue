<template>

  <a href="#modal-full" uk-toggle
    class="uk-button uk-button-default uk-width-1-1 uk-margin"
    @click="getForm()"
    :class="clases"
    >
    <i class="fas fa-check uk-float-left uk-text-success uk-text-large"></i>
    {{title}}
    <div v-if="discount" class="uk-float-right ms-star-xs">
      <span><i class="fas fa-certificate uk-text-warning"></i></span>
      <p>-{{discount}}%</p>
    </div>
  </a>

</template>

<script>
export default{
  props:['title', 'clases', 'discount'],
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
