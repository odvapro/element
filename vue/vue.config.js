module.exports = {
	outputDir:'../public/dist/',
	publicPath:'/element/',
	chainWebpack: config => {
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

	}
}

