<template>
  <div id="app">
    <h1>Todolist</h1>
    <div id="list">
      <ul>
      <li v-for="(todo, index) in todos" :key="index" type="none">
        <input type="checkbox">{{ todo }}
      </li>
    </ul>
    </div>
    
    <input type="text" v-model="newtodo">
    <button class="btn" @click="addTodo">新增</button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      newtodo: ""
    };
  },
  computed: {
    todos() {
      return this.$store.state.todos;
    }
  },
  methods: {
    addTodo() {
      this.$store.dispatch("add", this.newtodo);
    }
  }
};
</script>

<style scoped>
h1{
  padding: 0px 40px;
}
</style>
