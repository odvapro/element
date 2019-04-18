<template>
	<div class="detail">
		<div class="detail-head">
			<div class="detail-head-name">
				<div class="detail-icon-wrapper">
					<svg width="14" height="13">
						<use xlink:href="#tableicon"></use>
					</svg>
				</div>
				<div class="detail-name-wrapper">
					<div class="detail-head-label">New Element</div>
					<div class="detail-head-descr">{{ tableCode }}</div>
				</div>
			</div>
		</div>
		<div class="detail-feild" v-for="(column,columnCode) in $store.state.tables.selectedElement">
			<div class="detail-field-name">
				<span>{{ columnCode }}</span>
				<small>{{ columnCode }}</small>
			</div>
			<div class="detail-field-box">
				<MainField
					:params="{
						fieldName : column.fieldName,
						value     : column.value,
						settings  : $store.getters.getColumnSettings(tableCode, columns[columnCode], $store.state.tables.selectedElement)
					}"
				/>
			</div>
		</div>
	</div>
</template>
<script>
	import MainField from '@/components/fields/MainField.vue';
	export default
	{
		components: {MainField},
		data()
		{
			return {
				columns:{},
				tableCode:false
			}
		},
		mounted()
		{
			let requestParams = {
				select : {},
				where  : [],
				order  : [],
			};
			requestParams.select.from  = this.$route.params.tableCode;
			let primaryKeyCode         = this.$store.getters.getPrimaryKeyCode(this.$route.params.tableCode);
			requestParams.select.where = {
				operation:'and',
				fields:[
					{
						code      : primaryKeyCode,
						operation : 'IS',
						value     : this.$route.params.id
					}
				]
			}
			this.$store.dispatch('selectElement',requestParams);
			this.columns   = this.$store.getters.getColumns(this.$route.params.tableCode);
			this.tableCode = this.$route.params.tableCode;
		}
	}
</script>
<style lang="scss">
	.detail
	{
		padding: 23px 0 23px 21px;
	}
	.detail-head
	{
		display: flex;
		justify-content: space-between;
		align-items: flex-end;
		margin-bottom: 13px;
		padding-right: 95px;
	}
	.detail-head-name
	{
		display: flex;
		align-items: center;
	}
	.detail-icon-wrapper
	{
		margin-right: 3px;
		font-weight: 500;
		line-height: normal;
		font-size: 20px;
		color: #000000;
	}
	.detail-head-label
	{
		font-family: $rBold;
		font-size: 20px;
		color: #191C21;
		line-height: 22px;
		text-transform: capitalize;
	}
	.detail-head-descr
	{
		color: rgba(103, 115, 135, 0.4);
		font-size: 10px;
		text-transform: lowercase;
	}
	.detail-name-wrapper{padding-left:7px; }
	.detail-feild{
		min-height: 50px;
		display: flex;
		align-items: center;
	}
	.detail-field-name
	{
		width:200px;
		span
		{
			display:block;
			font-size: 12px;
			line-height: normal;
			color: #191C21;

		}
		small
		{
			display:block;
			font-size: 10px;
			line-height: normal;
			color: rgba(103, 115, 135, 0.4);
		}
	}
	.detail-field-box
	{
		position: relative;
		min-width: 200px;
		height: 49px;
	}
</style>