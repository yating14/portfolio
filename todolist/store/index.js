import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    todos: ["learn English",
     "buy lunch", 
     "make plans"],
    newtodo: ""
  },
  mutations: {
    addtodo(state, payload) {
      state.todos.push(payload)
      state.newtodo = ""
    }
  },
  actions: {
    add({ commit }, newtodo) {
      commit("addtodo", newtodo)
    }
  },
  modules: {}
})
