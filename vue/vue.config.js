module.exports = {
	outputDir:'../public/',
	publicPath: process.env.BASE_URL,
	chainWebpack: config =>
	{
		const oneOfsMap = config.module.rule('scss').oneOfs.store
		oneOfsMap.forEach(item => {
			item
				.use('sass-resources-loader')
				.loader('sass-resources-loader')
				.options({
					resources: 'src/assets/variables.scss',
				})
				.end()
		})
	},
	configureWebpack:
	{
		resolve:
		{
			extensions: ['.js', '.vue', '.json'],
			alias: {'vue$': 'vue/dist/vue.esm.js', }
		}
	}
}

