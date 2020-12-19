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
      lesson.tutor_id = payload.tutor_id,
      lesson.classroom_id = payload.classroom_id,
      // lesson.start = payload.start,
      // lesson.end = payload.end,
      lesson.comment = payload.comment,
      lesson.color = payload.color
    },
    // убрать урок из состояния
    REMOVE_LESSON(state, payload){
      var index = state.lessons.findIndex(lesson => lesson.id === payload);
      state.lessons.splice(index, 1);
    }
  },
  actions: {
    // получаем Уроки
    GET_LESSONS : async (context, payload) => {
      let {data} = await Axios.get('/api/v1/lesson-get', payload);
      context.commit('SET_LESSONS', data);
    },
    // отправка Урока
    SET_LESSON : async (context, payload) => {
      let {data} = await Axios.post('/api/v1/lesson-set', payload);
      if (data.success === 'true') {
        context.commit('PUSH_lESSON', data.data);
      } else {
        alert(data.msg);
      }
    },
    // изменение Урока в БД
    EDIT_LESSON: async (context, payload) => {
      let {data} = await Axios.patch('/api/v1/lesson-upd/' + payload.id, payload);
      let result = '';
      if (data.success === 'true') {
        context.commit('UPDATE_LESSON', data.data);
        result = true;
      } else {
        alert(data.msg);
        result = false;
      }
      return result;
    },
    // изменение Урока в БД
    EDIT_TIME: async (context, payload) => {
      let {data} = await Axios.patch('/api/v1/lesson-time-upd/' + payload.id, payload);
      if (data.success === 'true') {
        context.commit('UPDATE_LESSON', data.data);
      } else {
        alert(data.msg);
      }
    },
    // изменение Урока в БД
    COPY_LESSON: async (context, payload) => {
      let {data} = await Axios.post('/api/v1/lesson-copy/' + payload);
      context.commit('PUSH_lESSON', data);
    },
    // удаление урока в БД
    DEL_LESSON: async (context, payload) => {
      let {data} = await Axios.delete('/api/v1/lesson-del/' + payload);
      context.commit('REMOVE_LESSON', payload);
    },
  }
}
