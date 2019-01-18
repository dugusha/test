<template>
    <div class="hello">
        <div>
            <h3 v-html="name"></h3>
            <button @click="goMark()" style="width: 100%">书签</button>
        </div>
        <div v-for="item in list">
            <div v-bind:style="{ 'font-size':20+'px',color: item.status==2?'black':'#9E9E9E','text-align':'left' }" @click="goBook(item.id)">{{item.title}}</div>
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
                id:"",
                name:""
            }
        },
        mounted() {
            this.id = this.$route.query.id
            this.getContent(this.id);
        },
        methods: {
            goBook(id){
                this.$router.push({path: '/book',query:{ id:id}});
            },
            goMark(){
                this.$router.push({path: '/'});
            },
            getContent(id){
                this.auto = false
                axios.get("/menu/get?mark_id="+this.id).then((data)=>{
                    data=data.data.data
                    if(data!=null){
                        this.id = data.id
                        this.name = data.name
                        this.list = data.list
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
