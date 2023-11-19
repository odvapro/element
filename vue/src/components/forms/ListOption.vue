<template>
	<div class="list-option" @click="select">
		<span :style="{background:color}">
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
			current: {type: Boolean, default: false, },
			color: {type: String, default: 'rgba(124, 119, 145, 0.1)', },
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
				setTimeout(() => { this.$parent.selectEvent(); }, 100);
			},
			/**
			 * Remove item
			 */
			remove()
			{
				this.$emit('remove');
				setTimeout(() => { this.$parent.removeEvent(); }, 100);
			}
		}
	}
</script>
<style lang="scss">
	.list-option
	{
		position: relative;
		min-height: 20px;
		span
		{
			height:24px;
			line-height: 24px;
			padding:0 8px;
			background-color: rgba(124, 119, 145, 0.1);
			border-radius: 2px;
			font-size: 10px;
			margin-right: 4px;
			color: rgba(25, 28, 33, 0.70);
			white-space: nowrap;
			display:inline-flex;
			position: relative;
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
