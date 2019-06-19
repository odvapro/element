<template>
	<div class="em-node__item-wrapper">
		<template v-if="view == 'detail'">
			<input
				type="text"
				v-model="query"
				placeholder="Начните вводить"
				@keyup="getNodes"
			>
			<button
				v-for="nodeItem in list"
				@click="changeValue(nodeItem.id)"
			>{{ nodeItem.name }}</button>
		</template>
		<template v-else>
			<div v-if="fieldValue.id" class="em-node__item" @click="goToNode">
				{{ fieldValue.name }}
			</div>
			<span v-else class="el-empty">Empty</span>
		</template>
	</div>
</template>
<script>
	export default
	{
		props: ['fieldValue','fieldSettings','mode','view'],
		data()
		{
			return {
				list: [],
				query: ''
			};
		},
		methods:
		{
			/**
			 * Send change current value
			 */
			changeValue(newValue)
			{
				this.$emit('onChange', {
					value     : newValue,
					settings  : this.fieldSettings
				});
			},
			async getNodes()
			{
				var data = new FormData();

				data.append('nodeFieldCode', this.fieldSettings.nodeFieldCode);
				data.append('nodeTableCode', this.fieldSettings.nodeTableCode);
				data.append('nodeSearchCode', this.fieldSettings.nodeSearchCode);
				data.append('q', this.query);

				let result = await this.$axios({
					method : 'POST',
					data   : data,
					headers: { 'Content-Type': 'multipart/form-data' },
					url    : '/field/em_node/index/autoComplete/'
				});

				if (!result.data.success)
					return false;

				this.list = result.data.result;
			},
			goToNode()
			{
				if(!this.fieldValue.id)
					return;

				this.$router.push(this.fieldValue.url)
			}
		},
		mounted()
		{
			this.getNodes();
		}
	}
</script>
<style lang="scss">
	.em-node__item
	{
		padding: 4px 8px;
		background-color: rgba(124, 119, 145, 0.1);
		border-radius: 2px;
		font-size: 10px;
		margin-right: 2px;
		color: #7C7791;
		position: relative;
		cursor: pointer;
	}
</style>