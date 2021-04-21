<template>
	<transition name="popup-fade">
		<div class="popup-overlay" @click=closeByClickoutside v-if="visible" ref="popup" @keydown.esc=closeByEsc tabindex="1">
			<div
				ref=popupBlock
				class="popup-block"
				:class=popupBlockClasses
			>
				<div ref=popupClose class="popup-close" @click=close>
					<svg width="12" height="12">
						<use xlink:href="#plus-white"></use>
					</svg>
				</div>
				<slot></slot>
			</div>
		</div>
	</transition>
</template>
<script>
	export default
	{
		name: 'Popup',
		props:
		{
			visible:
			{
				type: Boolean,
				default: false
			},
			popupBlockClasses:
			{
				type: Array,
				default: () => ([]),
			},
		},
		methods:
		{
			/**
			 * Closes popup
			 */
			closeByClickoutside(e)
			{
				const allParentPopups = this.getAllParentElementBySelector(e.target, '.popup-block');
				for (let parentPopup of allParentPopups)
				{
					if (parentPopup === this.$refs.popupBlock)
						return true;
				}

				this.close();
			},
			getAllParentElementBySelector(node, parentSelector)
			{
				const selectedParents = [];

				let nextElement = node;
				while(nextElement)
				{
					nextElement = nextElement.parentNode ? nextElement.parentNode.closest(parentSelector) : null;

					if (nextElement) selectedParents.push(nextElement);
				}
				return selectedParents;
			},
			closeByEsc()
			{
				const innerPopup = this.$refs.popupBlock ? this.$refs.popupBlock.querySelector('.popup-block') : null;
				if (!innerPopup)
					this.close();
			},
			close()
			{
				this.$emit('update:visible', false);
			},
			keyup(event)
			{
				if (event.keyCode === 27)
					this.closeByEsc();
			},
		},
		mounted()
		{
			if (this.visible)
				document.body.appendChild(this.$el);
			document.addEventListener('keyup', this.keyup);
		},
		destroyed()
		{
			if (this.$el && this.$el.parentNode)
				this.$el.parentNode.removeChild(this.$el);
			 document.removeEventListener('keyup', this.keyup);
		}
	};
</script>
<style lang="scss">
	.popup-overlay
	{
		background: rgba(25,28,33,0.1);
		position: fixed;
		top:0px;
		left:0px;
		width:100%;
		height: 100%;
		overflow: auto;
		z-index: 1;
		display: flex;
		justify-content: center;
		align-items: center;
		flex-flow: wrap;
	}
	.popup-block
	{
		background: #fff;
		border-radius: 2px;
		padding:20px;
		width:550px;
		position: relative;
		box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.08);
		.detail-head__burger { display: none; }
	}
	.popup-close
	{
		position: absolute;
		top:20px;
		right:20px;
		cursor: pointer;
		transform: rotate(-45deg);
		svg{stroke:#959DAB;}
		&:hover svg{stroke:#758092;}
	}
	.popup-cont {margin-bottom: 20px; .inp{width:calc(100% - 36px); } }
	.popup-btns {text-align: right; button{margin-left:20px;} }
	.popup-fade-enter-active {transition: all .05s ease; }
	.popup-fade-leave-active {transition: all .05s cubic-bezier(1, -0.53, 0.405, 1.425); }
	.popup-fade-enter, .popup-fade-leave-to {transform: translateY(-5px); }
	.popup__name
	{
		font-style: normal;
		font-weight: 500;
		font-size: 16px;
		line-height: 21px;
		color: #191C21;
		margin-bottom: 10px;
		text-transform: capitalize;
	}
	.popup__field
	{
		height: 50px;
		display:flex;
		align-items: center;
	}
	.popup__field-name
	{
		font-style: normal;
		font-weight: normal;
		font-size: 12px;
		line-height: 16px;
		color: #191C21;
		width:200px;
	}
	.popup__field-input
	{
		.el-inp{width:200px;}
	}
	.popup__buttons
	{
		text-align: center;
		margin-top: 20px;
		button{margin-right:15px;}
	}
	.popup__field-error
	{
		display:block;
		font-style: normal;
		font-weight: normal;
		font-size: 8px;
		line-height: 11px;
		color: rgba(208, 18, 70, 0.4);
		margin-top: 2px;
	}
	@media (max-width: 768px)
	{
		.popup-block
		{
			min-height: 100vh;
			max-width: 100vw;
			width: 100%;
			display: flex;
			flex-direction: column;
		}
		.settings-popup-row-params
		{
			flex-grow: 1;
			display: flex;
			flex-direction: column;
		}
		.popup__field { flex-wrap: wrap; }
		.popup__field-name { width: 180px; }
		.popup__buttons
		{
			margin-top: auto;
			padding-top: 20px;
		}
	}
</style>
