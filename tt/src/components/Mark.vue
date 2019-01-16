<template>
    <div class="hello">
        <div v-for="item in list">
            <div @click="goMenu(item.id)" style="font-size:20px">
                <span style="text-align: left;float: left;width: 68%;">
                    {{item.name}}
                </span>
                <span style="text-align: right;float: left;width: 30%;">
                    更新<span style="color: red">{{item.count}}</span>章
                </span>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    export default {
        name: 'HelloWorld',
        data () {
            return {
                list:[],
                id:""
            }
        },
        mounted() {
            this.getContent();
        },
        methods: {
            goMenu(id){
                this.$router.push({path: '/menu',query:{ id:id}});
            },
            getContent(){
                axios.get("/mark/get").then((data)=>{
                    data=data.data.data
                    if(data!=null){
                        this.id = data.id
                        this.list = data
                    }
                }).catch((response) => {})
            },
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
