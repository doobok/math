import Axios from 'axios';

export default {
  state: {
    lessons: [],
  },
  getters: {
    lessons: state => {
      return state.lessons;
    }
  },
  mutations: {
    SET_LESSONS: (state, payload) => {
      state.lessons = payload;
    },
    // добавление Урока к текущему состоянию
    PUSH_lESSON: (state, payload) => {
      state.lessons.push(payload);
    },
    // обновление таска в состоянии после редактирования
    UPDATE_LESSON (state, payload) {
      const lesson = state.lessons.find(t => {
        return t.id === payload.id
      })
      lesson.name = payload.name,
      // lesson.start = payload.start,
      // lesson.end = payload.end,
      lesson.comment = payload.comment,
      lesson.color = payload.color
    },
  },
  actions: {
    // получаем Уроки
    GET_LESSONS : async (context, payload) => {
      let {data} = await Axios.get('/api/v1/lesson-get');
      context.commit('SET_LESSONS', data);
    },
    // отправка Урока
    SET_LESSON : async (context, payload) => {
      let {data} = await Axios.post('/api/v1/lesson-set', payload);
      context.commit('PUSH_lESSON', data);
    },
    // изменение Урока в БД
    EDIT_LESSON: async (context, payload) => {
      let {data} = await Axios.patch('/api/v1/lesson-upd/' + payload.id, payload);
      context.commit('UPDATE_LESSON', data);
    },
    // изменение Урока в БД
    EDIT_TIME: async (context, payload) => {
      let {data} = await Axios.patch('/api/v1/lesson-time-upd/' + payload.id, payload);
      context.commit('UPDATE_LESSON', data);
    },
  }
}
