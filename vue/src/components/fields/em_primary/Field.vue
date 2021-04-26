<template>
	<div class="em-primary-wr" @click="openEditPage()">
		<div class="em-primary">
			{{ fieldValue }}
		</div>
		<div class="em-primary-hint" v-if="view === 'table'">
			<div class="em-primary-hint__icon"><svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.14996 1.375H9.62496V3.85" stroke="#677387" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.73749 4.2625L9.62499 1.375" stroke="#677387" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M3.85 9.62496H1.375V7.14996" stroke="#677387" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.2625 6.73746L1.375 9.62496" stroke="#677387" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
			<div class="em-primary-hint__text">{{ $t('open') }}</div>
		</div>
	</div>
</template>
<script>
	export default
	{
		props: ['fieldValue','fieldSettings','mode', 'view'],
		data()
		{
			return {
				localFieldValue:''
			}
		},
		mounted()
		{
			this.localFieldValue = this.fieldValue;
		},
		methods:
		{
			/**
			 * Send change current value
			 */
			openEditPage(event)
			{
				this.$emit('openEdit');
			}
		}
	}
</script>
<style lang="scss">
	.em-primary-wr
	{
		position: relative;
		display: flex;
		align-items: center;
		width: 100%;
		height: 100%;
		cursor: pointer;
		&:hover
		{
			.em-primary-hint { opacity: 1; }
		}
	}
	.em-primary
	{
		white-space: nowrap;
		background: rgba(58, 132, 6, 0.04);
		border-radius: 2px;
		line-height: 25px;
		height: 25px;
		padding:0 15px;
		font-size: 12px;
		color: rgba(25, 28, 33, 0.7);
		display: inline-block;
		// &:hover{background: rgba(58, 132, 6, 0.1);}
	}
	.em-primary-hint
	{
		position: absolute;
		top: 50%;
		right: 0;
		transform: translateY(-50%);
		white-space: nowrap;
		border: 1px solid rgba(103, 115, 135, 0.1);
		border-radius: 3px;
		color: #677387;
		background-color: #fff;
		display: flex;
		align-items: center;
		padding: 5px 12px;
		opacity: 0;
		&:hover { background: #f0f1f3; }
	}
	.em-primary-hint__icon
	{
		font-size: 0;
		line-height: 0;
		margin-right: 8px;
	}
	.em-primary-hint__text
	{
		font-size: 10px;
		line-height: 13px;
	}
	.detail-field-box .em-primary
	{
		padding: 0;
		background-color: transparent;
	}
</style>
