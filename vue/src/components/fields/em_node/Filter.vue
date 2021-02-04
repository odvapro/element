<template>
	<div
		class="em-node__filter"
		v-if="showInput"
	>
		<List
			:settings="{placeholder: $t('select_an_option') }"
		>
			<template v-slot:selected>
				<ListOption
					v-if="localFieldValue.id"
					@remove="removeItem"
					:current=true
				>{{ localFieldValue.name }}</ListOption>
			</template>
			<ListOption
				v-for="listItem in list"
				:key="listItem.code"
				@select="selectItem(listItem)"
			>{{ listItem.name }}</ListOption>
		</List>
		<span class="em-node__filter-select-arrow">
			<svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M9.26399 0.171389L9.26396 0.171427L5.00466 4.43907L0.736982 0.171389C0.580928 0.0153346 0.327417 0.0153346 0.171362 0.171389C0.0153076 0.327444 0.0153076 0.580955 0.171362 0.737009L4.71346 5.27911C4.79126 5.3569 4.88943 5.39615 4.99628 5.39615C5.09399 5.39615 5.20081 5.35738 5.27909 5.27911L9.82063 0.737571C9.98584 0.581446 9.98569 0.327466 9.82962 0.171389C9.67356 0.0153346 9.42005 0.0153346 9.26399 0.171389Z" fill="#677387" stroke="#677387" stroke-width="0.1"/>
			</svg>
		</span>
	</div>
</template>
<script>
	export default
	{
		props: ['filter','settings'],
		data()
		{
			return {
				query: '',
				list: [],
				localFieldValue: false
			}
		},
		computed:
		{
			showInput()
			{
				let emptyCollations = ['IS EMPTY','IS NOT EMPTY'];
				if(emptyCollations.indexOf(this.filter.operation) != -1)
					return false;
				return true;
			}
		},
		methods:
		{
			/**
			 * Send change current value
			 */
			changeValue(newValue)
			{
				this.$emit('onChange', newValue);
			},
			async getNodes()
			{
				var data = new FormData();
				data.append('nodeFieldCode', this.settings.nodeFieldCode);
				data.append('nodeTableCode', this.settings.nodeTableCode);
				data.append('nodeSearchCode', this.settings.nodeSearchCode);
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
				this.localFieldValue = this.list.filter(item => {return Number(item.id) === Number(this.filter.value)})[0] || { id: false };
			},
			selectItem(listItem)
			{
				this.changeValue(listItem.id);
			},
			removeItem()
			{
				this.localFieldValue = { id: false };
				this.changeValue('');
			}
		},
		mounted()
		{
			this.getNodes();
		}
	}
</script>
<style lang="scss">
	.em-node__filter
	{
		position: relative;
		display: flex;
		align-items: center;
		border:1px solid rgba(103,115,135,0.4);
		height: 30px;
		padding-left: 4px;
		padding-right: 10px;
		border-radius: 2px;
		margin-right: 10px;
		min-width: 160px;
		&:hover{border: 1px solid rgba(103,115,135,0.7);}
	}
	.em-node__filter-select-arrow
	{
		top: 13px;
		right: 10px;
		margin-top: 1px;
		margin-left: 5px;
	}
</style>
