<template>
    <div class="hello" style="margin:5px;">
        <el-row v-for="item in list" :key="id" style="margin-top: 5px" @click.native="goMenu(item.id)">
            <el-col :span="14" :offset="2" align="left"> {{item.name}}</el-col>
            <el-col :span="8"  style="color: red">更新<span>{{item.count}}</span>章</el-col>
        </el-row>
    </div>
</template>

<script>
    import axios from 'axios'
    export default {
        name: 'HelloWorld',
        data () {
            return {
                list:[],
                id:"",
                color:"red"
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
