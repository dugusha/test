module.exports = {
    lintOnSave: false,
    devServer: {
        proxy: {
            '/api': {
                target: 'http://test.cn',
                changeOrigin: true,
            },
            '/assets': {
                target: 'http://test.cn',
                changeOrigin: true,
            },
        }
    },
    publicPath:"test",
}