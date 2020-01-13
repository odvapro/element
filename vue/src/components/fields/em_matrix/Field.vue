<template>
	<div class="em-matrix">
		<div class="em-matrix-fields" v-if="view=='detail'">
			<div class="em-matrix-row-head">
				<div class="em-matrix-head-field em-matrix-head-field__name">{{$t('name2')}}</div>
				<div class="em-matrix-head-field em-matrix-head-field__key">{{$t('key')}}</div>
				<div class="em-matrix-head-field em-matrix-head-field__edit">{{$t('edit')}}</div>
				<div class="em-matrix-head-field em-matrix-head-field__remove"></div>
			</div>
			<div class="em-matrix-row" v-for="i in 2">
				<div class="em-matrix-field em-matrix-field__name">Eve Leroy</div>
				<div class="em-matrix-field em-matrix-field__key">1</div>
				<input class="em-matrix-field em-matrix-field__edit" value="Eve Leroy">
				<div class="em-matrix-field em-matrix-field__remove">{{$t('remove')}}</div>
			</div>
			<div class="em-matrix-add">
				<svg width="12" height="12">
					<use xlink:href="#plus"></use>
				</svg>
				New
			</div>
		</div>
		<div v-else>
			...
		</div>
	</div>
</template>
<script>
	export default
	{
		props: ['fieldValue','fieldSettings','mode', 'view'],
	}
</script>
<style lang="scss">
	.detail-field-box .em-matrix
	{
		width: 100vw;
		margin-left: -14px;
	}
	.em-matrix-row,
	.em-matrix-row-head
	{
		display: grid;
		grid-template-columns: 82px 76px 118px auto;
		border-bottom: 1px solid rgba(103, 115, 135, 0.1);
	}
	.em-matrix-row
	{
		&:last-child
		{
			border-bottom: none;
		}
		&:hover .em-matrix-field__remove
		{
			opacity: 1;
		}
	}
	.em-matrix-row__head
	{

	}
	.em-matrix-head-field,
	.em-matrix-field
	{
		font-size: 10px;
		border-right: 1px solid rgba(103, 115, 135, 0.1);
		border-top: none;
		border-left: none;
		border-bottom: none;
		min-height: 31px;
		display: flex;
		align-items: center;
		&:last-child
		{
			border-right: none;
		}
	}
	.em-matrix-head-field
	{
		justify-content: center;
	}
	.em-matrix-field
	{
	}
	.em-matrix-field__name
	{
		justify-content: center;
	}
	.em-matrix-field__key
	{
		justify-content: center;
	}
	.em-matrix-field__edit
	{
		padding-left: 14px;
	}
	.em-matrix-field__remove
	{
		padding-left: 14px;
		color: #677387;
		cursor: pointer;
		opacity: 0;
		transition: all .125s;
	}
	.em-matrix-add
	{
		color: #677387;
		cursor: pointer;
	}
</style>