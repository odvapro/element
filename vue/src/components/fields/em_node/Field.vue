<template>
	<div class="em-node">
		<List :searchText.sync="query">
			<template v-slot:selected>
				<ListOption
					v-if="localFieldValue.id"
					@remove="removeItem"
				>{{ localFieldValue.name }}</ListOption>
			</template>
			<ListOption
				v-for="listItem in list"
				@select="selectItem(listItem)"
			>{{ listItem.name }}</ListOption>
		</List>

		<!-- <template v-if="view == 'detail'">
			<div v-if="fieldValue.id" class="em-node__item" @click="goToNode">
				{{ fieldValue.name }}
			</div>
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
		</template> -->
	</div>
</template>
<script>
	import List from '@/components/forms/List.vue';
	import ListOption from '@/components/forms/ListOption.vue';
	export default
	{
		components: { List, ListOption },
		props: ['fieldValue','fieldSettings','mode','view'],
		data()
		{
			return {
				list: [],
				query: '',
				localFieldValue:this.fieldValue
			};
		},
		watch:
		{
			query(value)
			{
				this.getNodes()
			}
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

			/**
			 * Get nodes from server
			 */
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
			},

			/**
			 * Выбор опции
			 */
			selectItem(listItem)
			{
				this.localFieldValue = listItem;
				this.$emit('onChange', {
					value: this.localFieldValue,
					settings: this.fieldSettings
				});
			},

			/**
			 * Удаление выбранной опции
			 */
			removeItem()
			{
				this.localFieldValue = '';
				this.$emit('onChange', {
					value     : this.localFieldValue,
					settings  : this.fieldSettings
				});
			}
		},
		mounted()
		{
			this.getNodes();
		}
	}
</script>
<style lang="scss">
	.em-node
	{
		width:100%;
		height:100%;
		position: absolute;
		top:0px;
		left:0px;
		cursor: pointer;
		.list{padding-left:10px; }
	}
	.detail-field-box .em-node .list{padding-left:0px;}
</style>