<template>
    <div id="app"   :style="'position: absolute;width: 100%;color:'+this.$root.color+';background:'+this.$root.background">
        <div style="float: left">
            <el-button icon="el-icon-setting" :style="'position: absolute;opacity:'+this.$root.opacity/10" @click="dialogVisible = true" circle></el-button>
        </div>
        <el-dialog title="提示" :visible.sync="dialogVisible" :before-close="handleClose">
            <el-row>
                <el-col :span="6">字体:</el-col>
                <el-col :span="18"><el-color-picker v-model="color" :predefine="predefineColors"></el-color-picker></el-col>
            </el-row>
            <el-row>
                <el-col :span="6">背景:</el-col>
                <el-col :span="18"><el-color-picker v-model="background" :predefine="predefineColors"></el-color-picker></el-col>
            </el-row>
            <el-row>
                <el-col :span="6">按钮:</el-col>
                <el-col :span="18"><el-input-number v-model="opacity"  controls-position="right" :min="1" :max="10" size="mini"></el-input-number></el-col>
            </el-row>
            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="changeSet()">确 定</el-button>
            </span>
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
                opacity:6,
            };
        },
        mounted() {
            this.handleClose()
        },
        created() {
            this.$root.color = "#f5deb3"
            this.$root.background = "#000000"
            this.$root.opacity = 6
        },
        methods: {
            handleClose() {
                this.dialogVisible = false
                this.color = this.$root.color
                this.background = this.$root.background
                this.opacity = this.$root.opacity
            },
            changeSet(){
                this.dialogVisible = false
                this.$root.color = this.color
                this.$root.background = this.background
                this.$root.opacity = this.opacity
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
</style>
