<template>
	<div class="checkbox">
		<label class="checkbox__label">
			<input type="checkbox" class="checkbox__input" v-model="isCheched" @change="change()"/>
			<span>
				<svg width="7" height="7">
					<use xlink:href="#check"></use>
				</svg>
			</span>
		</label>
	</div>
</template>
<script>
	export default
	{
		props:{
			checked:
			{
				type: Boolean,
				default: false
			},
		},
		data()
		{
			return {
				isCheched:false
			}
		},
		watch:
		{
			checked(newVal, oldVal)
			{
				this.isCheched = newVal;
			}
		},
		methods:
		{
			change()
			{
				this.$emit('update:checked',this.isCheched);
				this.$emit('change',this.isCheched);
			}
		},
		mounted()
		{
			this.isCheched = this.checked;
		}
	}
</script>
<style lang="scss">
	.checkbox__label
	{
		display: inline-block;
		position: relative;
		padding-left: 12px;
		font-size: 14px;
		height: 12px;
		color: #334D66;
		cursor: pointer;
	}
	.checkbox__input {visibility: hidden; position: absolute; }
	.checkbox__input:not(checked) + span
	{
		display: flex;
		align-items: center;
		justify-content: center;
		width: 13px;
		height: 13px;
		border: 1px solid rgba(103, 115, 135, 0.4);
		border-radius: 2px;
		position: absolute;
		left: 0;
		transition: border 0.3s;
		background-color: #fff;
	}
	.checkbox__input:checked + span
	{
		background: #7C7791;
		border: 1px solid #7C7791;
		background-repeat: no-repeat;
		background-size: contain;
		transition: background 0.3s;
		img
		{
			width: 7px;
			height: 7px;
			object-fit: contain;
		}
	}
	.checkbox__input:checked:hover + span
	{
		transition: background 0.3s;
		border: 1px solid rgba(103, 115, 135, 0.5);
	}
	.checkbox__input:not(checked):hover + span
	{
		border: 1px solid rgba(103, 115, 135, 0.8);
		transition: border 0.3s;
	}
</style>