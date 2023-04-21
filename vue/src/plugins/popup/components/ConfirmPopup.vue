<template>
	<Popup
		class="confirm-popup"
		:visible="visible"
		@update:visible="onCancel"
		:canCloseByEsc="false"
	>
			<div class="confirm-popup__text">{{ message }}</div>
			<div class="confirm-popup__btns">
				<button
					class="el-gbtn"
					@click="onConfirm"
					:class="'el-gbtn--' + confirmType"
				>
					{{ buttons.confirm.text }}
				</button>
				<button
					class="el-btn confirm-popup__right-margin"
					@click="onCancel"
					:class="'el-btn--' + cancelType"
				>
					{{ buttons.cancel.text }}
				</button>
			</div>
	</Popup>
</template>
<script>
	import mixin from'../mixin';

	export default
	{
		name: 'ConfirmPopup',
		props: {
			buttons: {
				type: Object
			},
			message: {
				type: String,
			},
			confirmType: {
				type: String,
				default: 'red'
			},
			cancelType: {
				type: String,
				default: 'gray'
			}
		},
		mixins: [mixin],
		methods:
		{
			onConfirm()
			{
				this.$emit('confirm')
			},

			onCancel()
			{
				this.$emit('cancel')
			}
		},
		/**
		 * Хук при рендере компонента
		 */
		mounted()
		{
			this.$modal.subscriber.$on(
				'hide',
				name =>
				{
					if(this.name != name)
						return;
					this.visible = false;
				}
			);

			if (typeof  this.text == 'undefined')
				return false;

			if (this.text != '')
				this.title = this.text;
		}
	}
</script>

<style lang="scss">
	.confirm-popup
	{
		& .popup-block
		{
			max-width: 327px;
			min-height: 0;
			padding: 30px;
			margin: 0 16px;
			border-radius: 2px;
		}

		& .popup-close {display: none;}
	}

	.confirm-popup__text
	{
		margin-bottom: 22px;
		font-weight: 400;
		font-size: 12px;
		line-height: 150%;
		color: #191C21;
	}

	.confirm-popup__btns
	{
		display: flex;
		flex-direction: column;

		& button
		{
			height: 36px;
			max-width: 267px;
			border-radius: 3px;

			&:first-child { margin-bottom: 15px; }

			&:last-child { margin-bottom: 15px; }
		}
	}
</style>