<template>
	<div class="el-emoji-picker">
		<Popup
			:visible.sync=isPickerVisible
			:popupBlockClasses="['el-emoji-picker__popup']"
		>
			<div class="el-emoji-picker__head">
				<div class="el-emoji-picker__title">Emoji</div>
				<div class="el-emoji-picker__buttons">
					<div
						@click="pick('')"
						class="el-emoji-picker__button el-emoji-picker__clear"
					>{{ $t('clear') }}</div>
					<div @click=close class="el-emoji-picker__button el-emoji-picker__cancel">{{ $t('cancel') }}</div>
				</div>
			</div>
			<div class="el-emoji-picker__content-wrapper">
				<div class="el-emoji-picker__content">
					<span
						class="el-emoji-picker__item"
						v-for="emoji of emojies"
						@click=pick(emoji.content)
					>{{ emoji.content }}</span>
				</div>
			</div>
		</Popup>
	</div>
</template>

<script>
	import emojies from '@/assets/emoji.json';
	export default
	{
		props: {
			visible: {
				type: Boolean,
				default: true,
			},
		},
		data()
		{
			return {
				emojies: [],
			};
		},
		computed:
		{
			isPickerVisible:
			{
				get()
				{
					return this.visible;
				},
				set(value)
				{
					this.$emit('update:visible', value);
				},
			},
		},
		mounted()
		{
			this.getEmojies();
		},
		methods:
		{
			pick(content)
			{
				this.$emit('pick', content);
				this.close();
			},
			getEmojies()
			{
				this.$set(this, 'emojies', emojies);
			},
			close()
			{
				this.isPickerVisible = false;
			},
		},
	};
</script>

<style lang="scss">
	.el-emoji-picker
	{
		background-color: #fff;
		border-radius: 2px;
	}
	.el-emoji-picker__popup
	{
		padding: 0;
		border: 1px solid rgba(103, 115, 135, 0.1);
		max-width: 328px;
		.popup-close { display: none; }
	}
	.el-emoji-picker__head
	{
		border-bottom: 2px solid rgba(103, 115, 135, 0.1);
		padding: 9px 16px 5px;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}
	.el-emoji-picker__buttons
	{
		display: flex;
	}
	.el-emoji-picker__button
	{
		cursor: pointer;
		color: rgba(103, 115, 135, 0.7);
		margin-right: 5px;
		text-transform: lowercase;
		&:last-child { margin-right: 0; }
	}
	.el-emoji-picker__title,
	.el-emoji-picker__button
	{
		font-size: 10px;
		line-height: 13px;
	}
	.el-emoji-picker__clear
	{
		color: rgba(208, 18, 70, 0.7);
	}
	.el-emoji-picker__content-wrapper
	{
		max-height: 195px;
		min-height: 195px;
		overflow-y: hidden;
		padding: 20px 16px;
		display: flex;
	}
	.el-emoji-picker__content
	{
		display: flex;
		flex-wrap: wrap;
		overflow-y: scroll;
		font-size: 0;
		line-height: 0;
		width: 100%;
	}
	.el-emoji-picker__item
	{
		font-size: 20px;
		line-height: 26px;
		flex-basis: 10%;
		text-align: center;
		cursor: pointer;
	}
	@media (max-width: 768px)
	{
		.el-emoji-picker__content-wrapper
		{
			max-height: calc(100vh - 35px);
		}
	}
</style>
