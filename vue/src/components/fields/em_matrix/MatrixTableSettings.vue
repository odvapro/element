<template>
	<div class="matrix-table-settings">
		<h2 class="popup__name">Visual table settings</h2>
		<div class="matrix-table-settings__line">
			<div class="matrix-table-settings__block matrix-table-settings__block--drag"></div>
			<div class="matrix-table-settings__block">Code</div>
			<div class="matrix-table-settings__block">Name</div>
			<div class="matrix-table-settings__block">Show field</div>
		</div>
		<draggable
			:list="fields"
		>
			<div v-for="field in fields" class="matrix-table-settings__line" :key="field.order">
				<div class="matrix-table-settings__block matrix-table-settings__block--drag">
					<svg width="8" height="12">
						<use xlink:href="#dotts-for-drag"></use>
					</svg>
				</div>
				<div class="matrix-table-settings__block">{{ field.key }}</div>
				<div class="matrix-table-settings__block">
					<input type="text" v-model="field.name" placeholder="Set Name">
				</div>
				<div class="matrix-table-settings__block">
					<Checkbox :checked.sync="field.visibility" />
				</div>
			</div>
		</draggable>
	</div>
</template>
<script>
	import draggable from 'vuedraggable'
	export default {
		components: { draggable },
		props: {
			fields: { type: Array, required: true, },
		},
		data() {
			return {
			};
		},
		mounted()
		{
		},
		methods:
		{
		}
	};
</script>
<style lang="scss">
	.matrix-table-settings__line
	{
		height: 40px;
		border-bottom: 1px solid rgba(103, 115, 135, 0.1);
		display: flex;
		align-items: center;
		&:first-child{border-top: 1px solid rgba(103, 115, 135, 0.1);}

	}
	.matrix-table-settings__block
	{
		width:230px;
		border-right:1px solid rgba(103, 115, 135, 0.1);
		height: 100%;
		padding-left:10px;
		line-height:40px;
		color:#677387;
		font-size: 12px;
		&--drag{ width:30px; cursor: grabbing; }
		&:last-child{ border-right: 0px;}
	}
	.matrix-table-settings__block input{
		border: 0px;
		&::placeholder{color: rgba(103, 115, 135, 0.4); }
	}
</style>