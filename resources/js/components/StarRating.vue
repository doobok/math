<template>
  <div>

    <i v-for="n in 5"
    class="fas fa-star uk-text-large db--pointer"
    @mouseover="hoverRating(n)"
    @mouseleave="hoverLeave()"
    @click="setRating(n)"
    v-bind:class="{ 'uk-text-warning': starStyle(n) }"></i>


    <template v-if="saverating > 0">
      <span class="uk-text-large uk-margin-small-left uk-margin-small-right uk-text-muted">{{saverating}}</span>
      <span v-if="voted" class="uk-text-meta">- Ваш голос</span>
      <span class="uk-text-meta"
        v-bind:class="{ 'uk-hidden': voted }"
        ><i class="fas fa-user-times"></i>
        <span>{{count}}</span>
      </span>
    </template>

  </div>
</template>

<script>
export default {
  props: ['itemid', 'model'],
  data(){
      return{
        rating: 0,
        saverating: 0,
        count: 1,
        voted: false,

    }
  },
  mounted: function (){

        this.getRating();

  },
  methods: {
    findRateLocal() {
      if (localStorage.getItem('stars')) {
        try {
          let stars = JSON.parse(localStorage.getItem('stars'));
          if (stars) {
            let myVote = stars.find(star => star.itemid === this.itemid, star => star.model === this.model).rating;
            if (myVote != null) {
              this.voted = true;
              this.rating = myVote;
              this.saverating = myVote;
              // console.log(myVote);
            }
          }

        } catch(e) {
          // console.log('Ошибка чтения рейтинга' + e);
          // console.log(typeof(myVote));
          // localStorage.removeItem('stars');
        }
      }
    },
    starStyle(n) {
      if (this.rating >= n) {
        return true;
      }
    },
    hoverRating(n) {
      if (!this.voted) {
        this.rating = n;
      }
    },
    hoverLeave() {
      if (!this.voted) {
        this.rating = this.saverating;
      }
    },
    getRating() {
      axios
            .get('/api/v1/rating-get', { params: {itemid: this.itemid, model: this.model}})
            .then(response => {
                this.rating = response.data.rating;
                this.saverating = response.data.rating;
                this.count = response.data.count;
                this.findRateLocal();
            });
    },
    setRating(n) {
      if (!this.voted) {

        axios
            .post('/api/v1/rating-set', {itemid: this.itemid, model: this.model, rating: n, count: this.count})
            .then(response => {
              this.voted = true,
              this.saverating = n;

              // set to localStorage
              let vote = {itemid: this.itemid, model: this.model, rating: n};
              this.SaveDataToLocalStorage(vote);

            })
            .catch(err => {
              let e = { ...err    }
              console.log(e);
              alert('Не удалось проголосовать, попробуйте повторить попытку позже');
            });
      }
    },
    SaveDataToLocalStorage(data)
    {
        var a = [];
        // сохранение в массив в localStorage
        a = JSON.parse(localStorage.getItem('stars')) || [];
        a.push(data);
        localStorage.setItem('stars', JSON.stringify(a));
    }
  }

}
</script>
