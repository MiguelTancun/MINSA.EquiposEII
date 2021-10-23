/*=========================================================================================
  File Name: moduleCalendarActions.js
  Description: Calendar Module Actions
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

import axios from '@/axios.js'
import store from '../store'
export default {
  // addItem({ commit }, item) {
  //   return new Promise((resolve, reject) => {
  //     axios.post("/api/data-list/products/", {item: item})
  //       .then((response) => {
  //         commit('ADD_ITEM', Object.assign(item, {id: response.data.id}))
  //         resolve(response)
  //       })
  //       .catch((error) => { reject(error) })
  //   })
  // },
  fetchUsers ({ commit }) {
    return new Promise((resolve, reject) => {
      const token = store.state.token
      axios
        .get('/api/user-management/users', {
          headers: {
            company_id: localStorage.getItem('rtmCompany'),
            Authorization: `Bearer ${token}`
          }
        })
        .then(response => {
          commit('SET_USERS', response.data)
          resolve(response)
        })
        .catch(error => {
          reject(error)
        })
    })
  },
  fetchUser (context, userId) {
    return new Promise((resolve, reject) => {
      const token = store.state.token
      axios
        .get(`/api/user-management/users/${userId}`, {
          headers: {
            company_id: localStorage.getItem('rtmCompany'),
            Authorization: `Bearer ${token}`
          }
        })
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  },
  removeRecord ({ commit }, userId) {
    return new Promise((resolve, reject) => {
      axios
        .delete(`/api/user-management/users/${userId}`)
        .then(response => {
          commit('REMOVE_RECORD', userId)
          resolve(response)
        })
        .catch(error => {
          reject(error)
        })
    })
  }
}
