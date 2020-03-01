<template>
	<transition name="popup-fade">
		<div class="popup-overlay" @click="close" v-if="visible" ref="popup" @keydown.esc="close" tabindex="1">
			<div
				class="popup-block">
				<div class="popup-close" @click="close">
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
		},
		methods:
		{
			/**
			 * Closes popup
			 */
			close(e)
			{
				let blockInPathIndex = e.path.findIndex(el=>{return document.querySelector('.popup-block') === el});
				let closeInPathIndex = e.path.findIndex(el=>{return document.querySelector('.popup-close') === el});

				if (blockInPathIndex === -1 || closeInPathIndex !== -1)
					this.$emit('update:visible', false);
			},
			keyup(event)
			{
				if (event.keyCode === 27)
					this.close();
			}
		},
		mounted()
		{
			if (this.visible)
			{
				document.body.appendChild(this.$el);
			}
		},
		created()
		{
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
	}
	.popup-block
	{
		background: #fff;
		border-radius: 2px;
		padding:20px;
		width:550px;
		position: relative;
		box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.08);
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
</style>