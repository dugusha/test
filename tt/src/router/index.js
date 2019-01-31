import Vue from 'vue'
import Router from 'vue-router'
import Mark from '@/components/Mark'
import Menu from '@/components/Menu'
import Book from '@/components/Book'
import Ee from '@/components/Ee'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'

Vue.use(Router)
Vue.use(ElementUI)

export default new Router({
  routes: [
      {
          path: '/',
          name: 'Mark',
          component: Mark
      },
      {
          path: '/menu',
          name: 'Menu',
          component: Menu
      },
      {
          path: '/book',
          name: 'Book',
          component: Book
      },
      {
          path: '/e',
          name: 'ee',
          component: Ee
      }
  ]
})
