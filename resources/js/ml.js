import Vue from 'vue'
import { MLInstaller, MLCreate, MLanguage } from 'vue-multilanguage'

Vue.use(MLInstaller)

export default new MLCreate({
  initial: 'ru',
  languages: [
    new MLanguage('ru').create({
        phone_sended: 'Номер телефона отправлен',
        phone_sended_desc: 'Мы получили номер Вашего телефона. Пожалуйста, оставайтесь на связи.',
        input_phone_desc: 'Осталось оставить контактный номер и мы Вам перезвоним для уточнения всех подробностей',
        pls_correct_phone: 'Пожалуйста, введите действительный номер телефона',
        enter_phone: 'Введите номер',
        send: 'Отправить',
        trust: 'мы никогда не передадим номер телефона третьим лицам',
    }),

    new MLanguage('uk').create({
        phone_sended: 'Номер телефону відправлений',
        phone_sended_desc: 'Ми отримали номер Вашого телефону. Будь ласка, залишайтеся на звʼязку.',
        input_phone_desc: 'Залишилося залишити контактний номер і ми Вам зателефонуємо для уточнення всіх подробиць',
        pls_correct_phone: 'Будь ласка, введіть дійсний номер телефону',
        enter_phone: 'Введіть номер',
        send: 'Надіслати',
        trust: 'ми ніколи не передамо номер телефону третім особам',
    })
  ]
})
