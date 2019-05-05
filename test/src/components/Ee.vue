<template>
  <div class="hello">
      <div style="border: 1px solid red;width:100px;height:100px">
          <h1>{{a.b}}</h1></div>
      <div style="border: 1px solid red;width:500px;height:500px">
          <input v-model="a.b"/>
          <button @click="click">click</button>
      </div>
  </div>
</template>

<script>
    import axios from 'axios'
    export default {
        name: 'HelloWorld',
        data() {
            return {
                msg:"222323232",
                a : {
                    b:1,
                    c:2
                },
                a1 : {
                    b:3,
                    c:4
                }
            }
        },
        mounted() {
            this.a1 = this.a

//            this.click()
        },
        methods: {
            click(){
                axios.get("/api/smart-pic/get-frame").then((data)=>{
                    console.log(data.data.data[0]["key"])
                    this.a.b = data.data.data[0]["key"]
                }).catch((response) => {})
            }
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1, h2 {
  font-weight: normal;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>
