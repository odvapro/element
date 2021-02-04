<template>
	<div class="list-option" @click="select">
		<span>
			<slot></slot>
			<svg width="9" height="9" @click.stop="remove" class="list-option__remove">
				<use xlink:href="#plus-white"></use>
			</svg>
		</span>
	</div>
</template>
<script>
	export default
	{
		props:
		{
			current: {
				type: Boolean,
				default: false,
			},
		},
		methods:
		{
			/**
			 * Choose option and close dropdown
			 */
			select:function(event)
			{
				if (this.current)
					return;
				this.$emit('select');
				setTimeout(() => { this.$parent.closePopup(); }, 100);
			},
			/**
			 * Remove item
			 */
			remove()
			{
				this.$emit('remove');
			}
		}
	}
</script>
<style lang="scss">
	.list-option
	{
		position: relative;
		width:100%;
		min-height: 20px;
		span
		{
			top:50%;
			margin-top: -10px;
			height:20px;
			line-height: 20px;
			padding:0 8px;
			background-color: rgba(124, 119, 145, 0.1);
			border-radius: 2px;
			font-size: 10px;
			margin-right: 2px;
			color: #7C7791;
			position: absolute;
			white-space: nowrap;
			text-overflow: ellipsis;
			max-width: calc(100% - 20px);
			overflow: hidden;
		}
	}
	.list-option__remove
	{
		display: none;
		position: absolute;
		top:50%;
		margin-top: -5px;
		right:5px;
		margin-left: 2px;
		stroke:#677387;
		transform: rotate(45deg);
	}
</style>
