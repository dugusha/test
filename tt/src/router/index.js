import Vue from 'vue'
import Router from 'vue-router'
import Mark from '@/components/Mark'
import Menu from '@/components/Menu'
import Book from '@/components/Book'
import Ee from '@/components/Ee'

Vue.use(Router)

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
