<template>
    <div class="hello">
        <div v-for="item in list">
            <h3 v-html="item.title"></h3>
            <div style="text-align: left" v-html="item.content"></div>
            <div>
                <button @click="goMenu(item.mark_id)">目录</button>
                <button @click="getContent()">重新拉取</button>
            </div>
        </div>
        <div style="text-align: right">
            <button @click="getContent()">下一页</button>
        </div>
        <div v-if="!auto">
            没有了
        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
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
                auto: true
            }
        },
        mounted() {
            window.addEventListener('scroll', this.scroll)
            this.id = this.$route.query.id
            this.getContent(this.id);
        },
        methods: {
            scroll(){
                if(!this.auto) return
                if((document.documentElement.scrollHeight-this.getNowHight())<100){
                    this.getContent()
                }
            },
            getNowHight(){
                let clientHeight = 0;
                if(document.body.clientHeight && document.documentElement.clientHeight) {
                    clientHeight = (document.body.clientHeight < document.documentElement.clientHeight) ? document.body.clientHeight : document.documentElement.clientHeight;
                } else {
                    clientHeight = (document.body.clientHeight > document.documentElement.clientHeight) ? document.body.clientHeight : document.documentElement.clientHeight;
                }
                return clientHeight + document.documentElement.scrollTop
            },
            goMenu(id){
                this.$router.push({path: '/menu',query:{ id:id}});
            },
            getContent(id){
                this.auto = false
                axios.get("/book/get?id="+this.id+"&getNext="+(id != this.id)).then((data)=>{
                    data=data.data.data
                    if(data!=null){
                        this.id = data.id
                        this.list.push({
                            title: data.title,
                            content: data.content.replace(/\n/gm,"<br/>").replace(/ /gm,"&nbsp&nbsp")
                        })
                        this.auto = true
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
