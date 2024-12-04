<template>
	<div class="em-string__wrapper">
		<input
			class="em-string__edit"
			v-model="localValue"
			placeholder="Empty"
			@change="changeValue"
			v-mask="mask"
		/>
	</div>
</template>
<script>
	export default
	{
		props: ['fieldValue','fieldSettings','mode', 'view'],
		data()
		{
			return {
				localValue:false,
				mask:false
			}
		},
		mounted()
		{
			this.localValue = this.fieldValue;
			if(this.fieldSettings.useMask && this.fieldSettings.mask != '')
				this.mask = this.fieldSettings.mask;
		},
		methods:
		{
			/**
			 * Send change current value
			 */
			changeValue()
			{
				this.$emit('onChange', {
					value     : this.localValue,
					settings  : this.fieldSettings
				});
			},
			onEditString(e)
			{
				this.fieldValue =  e.target.innerText;
			},
		}
	}
</script>
<style lang="scss">
	.em-string__wrapper
	{
		height: 100%;
		width: 100%;
		position: absolute;
		left: 0px;
		top:0px;
	}
	.detail-field-box .em-string{line-height: 50px;}

	.em-string__edit
	{
		width:100%;
		height: 100%;
		border:0px;
		padding-left: 10px;
		padding-right: 10px;

		overflow: hidden;
		text-overflow: ellipsis;
		z-index: 1;
		border-radius: 2px;
		line-height: 18px;
		font-size: 12px;
		color: #677387;
		&::placeholder{ color: rgba(103, 115, 135, 0.8); }
		&:hover{ background:rgba(103, 115, 135, 0.01); }
		&:focus
		{
			position: absolute;
			top:-5px;
			left:-5px;
			width:calc(100% + 10px);
			min-width: 169px;
			min-height: 60px;
			background: #fff;
			padding-left:10px;
			z-index: 1;
			border-radius: 2px;
			box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
			border: 1px solid rgba(103, 115, 135, 0.1);
			padding-bottom:10px;
			padding-top:10px;
		}
	}
</style>
