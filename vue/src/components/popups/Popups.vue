<template>
	<transition name="popup-fade">
		<div class="popup-overlay" v-if="$store.state.openPopup">
			<div v-click-outside="close" class="popup-block">
				<div class="popup-close" @click="close()">
					<svg width="12" height="12">
						<use xlink:href="#plus-white"></use>
					</svg>
				</div>
				<component v-bind:is="contentComponent"></component>
			</div>
		</div>
	</transition>
</template>
<script>
	export default
	{
		computed:
		{
			/**
			 * Подключает файл для контента попапа
			 */
			contentComponent()
			{
				if(!this.$store.state.openPopupName)
					return null;
				return () => import(`@/components/popups/${this.$store.state.openPopupName}`);
			}
		},
		methods:
		{
			/**
			 * Закрытие попапа
			 */
			close()
			{
				this.$store.commit('closePopup');
			}
		}
	}
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
	}
	.popup-block
	{
		background: #fff;
		border-radius: 2px;
		padding:20px;
		width:480px;
		margin:0 auto;
		margin-top:100px;
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
	.popup__name{
		font-style: normal;
		font-weight: 500;
		font-size: 16px;
		line-height: 21px;
		color: #191C21;
	}
</style>