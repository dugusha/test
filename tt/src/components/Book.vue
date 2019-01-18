<template>
    <div class="hello">
        <div v-for="(item,index) in list">
            <h3 v-html="item.title"></h3>
            <div style="text-align: left" v-html="item.content"></div>
            <div>
                <button @click="goMark()">书签</button>
                <button @click="goMenu()">目录</button>
                <button @click="refresh(index)">重新拉取</button>
            </div>
        </div>
        <div style="text-align: right">
            <button style="width: 100%" @click="getContent()">下一页</button>
        </div>
        <div v-if="auto==1">
            加载中
            <button style="width: 100%" @click="goMark()">书签</button>
        </div>
        <div v-if="auto==2">
            没有了
            <button style="width: 100%" @click="goMark()">书签</button>
        </div>
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
                mark_id:"",
                auto: 1
            }
        },
        mounted() {
            window.addEventListener('scroll', this.scroll)
            this.id = this.$route.query.id
            this.getContent(this.id);
        },
        methods: {
            scroll(){
                if(this.auto!=0) return
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
                return clientHeight + document.documentElement.scrollTop+document.body.scrollTop;
            },
            goMenu(){
                this.$router.push({path: '/menu',query:{ id:this.mark_id}});
            },
            goMark(){
                this.$router.push({path: '/'});
            },
            refresh(index){
                this.auto = 1
                axios.get("/book/refresh?id="+this.list[index]['id']).then((data)=>{
                    data=data.data.data
                    if(data!=null){
                        this.list[index] = {
                            id: data.id,
                            title: data.title,
                            content: data.content.replace(/\n/gm,"<br/>").replace(/ /gm,"&nbsp&nbsp")
//                            title: "bbb",
//                            content: "bbb<br>bbb<br>bbb<br>bbb<br>bbb<br>bbb<br>bbb<br>"
                        }
                        this.auto = 0
                        this.$forceUpdate()
                    }
                }).catch((response) => {})
            },
            getContent(id){
                this.auto = 1
                axios.get("/book/get?id="+this.id+"&getNext="+(id != this.id)).then((data)=>{
                    data=data.data.data
                    if(data!=null){
                        this.id = data.id
                        this.mark_id = data.mark_id
                        this.list.push({
                            id: data.id,
                            title: data.title,
                            content: data.content.replace(/\n/gm,"<br/>").replace(/ /gm,"&nbsp&nbsp")
//                            title: "aaaaaa",
//                            content: "abcdf<br>abcdf<br>abcdf<br>abcdf<br>abcdf<br>abcdf<br>abcdf<br>abcdf<br>abcdf<br>abcdf<br>abcdf<br>abcdf<br>abcdf<br>abcdf<br>abcdf<br>abcdf<br>abcdf<br>abcdf<br>abcdf<br>abcdf<br>abcdf<br>"
                        })
                        this.auto = 0
                    }else {
                        this.auto = 2
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
