import Axios from 'axios';

export default {
  state: {
    slug: "",
    group: false,
    pdomodata: [],
    price: '',
    discount: ''
  },
  getters: {
    slug: state => {
      return state.slug;
    },
    group: state => {
      return state.group;
    },
    price: state => {
      return state.price;
    },
    discount: state => {
      return state.discount;
    }
  },
  mutations: {
    SET_PROMO: (state, payload) => {
      state.pdomodata = payload;
    },
    CALC_PROMO: (state) => {
      if (state.group) {
        state.price = state.pdomodata.price2;
        state.discount = state.pdomodata.discountg;
      } else {
        state.price = state.pdomodata.price1;
        state.discount = state.pdomodata.discount;
      }
    },
    SET_SLUG: (state, payload) => {
      // console.log(payload);
      state.slug = payload;
    },
    SET_GROUP: (state, payload) => {
      // console.log(payload);
      state.group = payload;
    },
  },
  actions: {
    // получение Promo из БД
    GET_PROMO : (context, payload) => {
      return Axios.get('/api/v1/promo')
      .then((response) => {
        context.commit('SET_PROMO', response.data);
        context.commit('CALC_PROMO');
        // return response.data
      })
      .catch(error => {
        return error;
      });
    },
    // Сохранение фразы с кнопки
    PUSH_SLUG : (context, payload) => {
        context.commit('SET_SLUG', payload);
    },
    // пеереключение групового занятия
    PUSH_GROUP : (context, payload) => {
        context.commit('SET_GROUP', payload);
        context.commit('CALC_PROMO');
    },

    // отправка лида
    SEND_LEAD : (context, payload) => {
      // console.log(payload);
      return Axios.post('/api/v1/lead-push', tempyGen(payload))
      .then((response) => {
        // какоето действие из состоянием (оставил на будущее)
        // if (response.success === true) {
        //   context.commit('SOME_MUTATOR', response.data);
        // }
        return response.data
      })
      .catch(error => {
        console.log(error);
        return error;
      });
    }

  }
}

function tempyGen(payload) {
  // проверяем наличие персонального идентификатора
  let tempy;
  if (localStorage.getItem('tempy')) {
    try {
      tempy = JSON.parse(localStorage.getItem('tempy'));
    } catch(e) {
      localStorage.removeItem('tempy');
    }
  } else {
    // если не обнаружен
    // генерируем случайную строку
    let s = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    tempy = Array(16).join().split(',').map(function() { return s.charAt(Math.floor(Math.random() * s.length)); }).join('');

    // сохраняем в браузер пользователя
    const parsed = JSON.stringify(tempy);
    localStorage.setItem('tempy', parsed);
  };
  // добавляем к данным из формы
  payload.tempy = tempy;

  return payload;
}
