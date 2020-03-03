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
		config.module
		.rule('vue')
		.use('vue-loader')
		.tap(options => {
			options.compilerOptions.modules = [
				{
					preTransformNode(astEl) {
						if (process.env.NODE_ENV === 'production') {
							const { attrsMap, attrsList } = astEl;
							let reg = /data-test.*/;
							for (let attr in attrsMap)
							{
								if (!reg.test(attr))
									continue;

								delete attrsMap[attr];
								const index = attrsList.findIndex(x => x.name === attr);
								attrsList.splice(index, 1);
							}
						}
						return astEl;
					}
				}
			];
			return options;
		});
	},
	configureWebpack:
	{
		resolve:
		{
			extensions: ['.js', '.vue', '.json'],
			alias: {'vue$': 'vue/dist/vue.esm.js', }
		}
	},
}

