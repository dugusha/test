<template>
    <div id="app"   :style="'position: absolute;width: 100%;color:'+this.$root.color+';font-size:'+this.$root.textSize+'px'">
        <div :style="'z-index: -99999;top: 0;left: 0;position: fixed;width: 100%;height: 100%;background: blue'+';background:'+this.$root.background"></div>
        <div class="xuanfu" id="moveDiv"
             @mousedown="down" @touchstart="down"
             @mousemove="move" @touchmove="move"
             @mouseup="end" @touchend="end"
        >
                <el-button icon="el-icon-setting" :style="'opacity:'+this.$root.opacity/100" @click="dialogVisible = true" circle></el-button>
        </div>
        <el-dialog title="设置" width="80%" :visible.sync="dialogVisible" :before-close="handleClose">
            <el-row>
                <el-col :span="12">
                    <el-button type="primary" @click="goMark" size="small" round>书签</el-button>
                </el-col>
                <el-col :span="12">
                    <el-button type="success" @click="goMenu" size="small" round>目录</el-button>
                </el-col>
            </el-row>
            <el-row>
                &nbsp
            </el-row>
            <el-row>
                <el-col :span="6">字体:</el-col>
                <el-col :span="18"><el-color-picker @change="changeSet" size="mini" v-model="color" :predefine="predefineColors"></el-color-picker></el-col>
            </el-row>
            <el-row>
                <el-col :span="6">背景:</el-col>
                <el-col :span="18"><el-color-picker @change="changeSet" size="mini" style="width: 100%" v-model="background" :predefine="predefineColors"></el-color-picker></el-col>
            </el-row>
            <el-row>
                <el-col :span="6">按钮:</el-col>
                <el-col :span="18"><el-slider :step="10" @change="changeSet" v-model="opacity" :format-tooltip="formatTooltip"></el-slider></el-col>
            </el-row>
            <el-row>
                <el-col :span="6">字体:</el-col>
                <el-col :span="18"><el-input-number size="mini" v-model="textSize" @change="changeSet" :min="12" :max="40"></el-input-number></el-col>
            </el-row>
        </el-dialog>
        <router-view />
    </div>
</template>

<script>
    export default {
        name: 'App',
        data() {
            return {
                dialogVisible: false,
                predefineColors: [
                    '#ffffff',
                    '#f5f5f5',
                    '#f5deb3',
                    '#ffd700',
                    '#90ee90',
                    '#00ced1',
                    '#1e90ff',
                    '#c71585',
                    '#deb887',
                    '#000000',
                ],
                color : "black",
                background : "white",
                opacity:60,
                textSize:20,
                position: { x: 0, y: 0 },
                nx: '', ny: '', dx: '', dy: '', xPum: '', yPum: '',
            };
        },
        mounted() {
            this.color = this.$root.color
            this.background = this.$root.background
            this.opacity = this.$root.opacity
            this.textSize = this.$root.textSize
        },
        created() {
            if(this.getCookie("color") == undefined){
                this.setCookie("color","#f5deb3",5)
                this.$root.color = "#f5deb3"
            }else {
                this.$root.color = this.getCookie("color")
            }
            if(this.getCookie("background") == undefined){
                this.setCookie("background","#000000",5)
                this.$root.background = "#000000"
            }else {
                this.$root.background = this.getCookie("background")
            }
            if(this.getCookie("textSize") == undefined){
                this.setCookie("textSize",20,5)
                this.$root.textSize = 20
            }else {
                this.$root.textSize = this.getCookie("textSize")
            }
            this.$root.opacity = 60
        },
        methods: {
            handleClose() {
                this.dialogVisible = false
            },
            changeSet(){
                this.$root.color = this.color
                this.setCookie("color",this.color,5);

                this.$root.background = this.background
                this.setCookie("background",this.background,5);

                this.$root.textSize = this.textSize
                this.setCookie("textSize",this.textSize,5);

                this.$root.opacity = this.opacity
            },
            setCookie(key,value,t)
            {
                var oDate=new Date();
                oDate.setDate(oDate.getDate()+t);
                document.cookie=key+"="+value+"; expires="+oDate.toDateString();

            },
            getCookie(key){
                var arr1=document.cookie.split("; ");//由于cookie是通过一个分号+空格的形式串联起来的，所以这里需要先按分号空格截断,变成[name=Jack,pwd=123456,age=22]数组类型；
                for(var i=0;i<arr1.length;i++){
                    var arr2=arr1[i].split("=");//通过=截断，把name=Jack截断成[name,Jack]数组；
                    if(arr2[0]==key){
                        return decodeURI(arr2[1]);
                    }
                }
            },
            down(){
                window.addEventListener("touchmove",this.nomove,{ passive: false })
                this.flags = true;
                var touch;
                if(event.touches){
                    touch = event.touches[0];
                }else {
                    touch = event;
                }
                this.position.x = touch.clientX;
                this.position.y = touch.clientY;
                this.dx = moveDiv.offsetLeft;
                this.dy = moveDiv.offsetTop;
            },
            move(){
                if(this.flags){
                    var touch ;
                    if(event.touches){
                        touch = event.touches[0];
                    }else {
                        touch = event;
                    }
                    this.nx = touch.clientX - this.position.x;
                    this.ny = touch.clientY - this.position.y;
                    this.xPum = this.dx+this.nx;
                    this.yPum = this.dy+this.ny;
                    moveDiv.style.left = this.xPum+"px";
                    moveDiv.style.top = this.yPum +"px";
                }
            },
            end(){
                window.removeEventListener("touchmove",this.nomove,{ passive: false })
                this.flags = false;
            },
            nomove(){
                event.preventDefault()
            },
            goMark(){
                this.$router.push({path: '/'});
            },
            goMenu(){
                if(this.$root.mark_id==undefined){
                    alert("没有小说访问记录")
                }else {
                    this.$router.push({path: '/menu',query:{ id:this.$root.mark_id}});
                }
            },
            formatTooltip(val) {
                return val+"%";
            }
        }
    }
</script>

<style>
    #app {
        font-family: 'Avenir', Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: #2c3e50;
    }
    .xuanfu {
         height: 40px;
         width: 40px;
         /* 如果碰到滑动问题，1.3 请检查 z-index。z-index需比web大一级*/
         z-index: 999;
         position: fixed;
         top: 0;
         /*background-color: rgba(0, 0, 0, 0.55);*/
     }
</style>