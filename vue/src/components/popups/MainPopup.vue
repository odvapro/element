<template>
	<transition name="main-popup-fade">
		<div class="main-popup-overlay" v-if="$store.state.openPopup">
			<div v-click-outside="close" id="main-popup-wrapper"
				:style="styles">
				<component v-bind:is="contentComponent" :styles="getStyles" @width="setWidth"></component>
			</div>
		</div>
	</transition>
</template>
<script>
	export default
	{
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				popupWidth: 0
			};
		},
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
			},
			/**
			 * Достать все стили попапа
			 */
			getStyles()
			{
				return this.$store.state.popupCoords;
			},
			/**
			 * Задать стили попапа
			 */
			styles()
			{
				/**
				 * параметры отображения попапа
				 */
				switch(this.$store.state.popupCoords.positionType)
				{
					case 'center-bottom':
						return {
							left    : (this.$store.state.popupCoords.left - (this.popupWidth / 2 - this.$store.state.popupCoords.width / 2)) + 'px',
							top     : this.$store.state.popupCoords.top + 'px',
							display : 'block'
						};
						break;
					default:
						return {
							left    : this.$store.state.popupCoords.left + 'px',
							top     : this.$store.state.popupCoords.top + 'px',
							display : 'block'
						};
						break;
				}
			}
		},
		methods:
		{
			/**
			 * Достать ширину попапа
			 */
			setWidth(newWidth)
			{
				this.popupWidth = newWidth;
			},
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
<style>
	#main-popup-wrapper
	{
		position: absolute;
		display: none;
		background-color: #fff;
	}
	.main-popup-overlay
	{
		position: fixed;
		top:0px;
		left:0px;
		width:100%;
		height: 100%;
		z-index: 100;
	}
	.main-popup-block
	{
		background: #fff;
		padding:30px;
		width:480px;
		margin:0 auto;
		margin-top:100px;
		position: relative;
	}
	.main.popup-close
	{
		position: absolute;
		top:20px;
		right:20px;
		cursor: pointer;
		/*svg{fill:#677387;}
		&:hover svg{fill:#191C21;}*/
	}
	.main.popup-title
	{
		margin-bottom: 20px;
		h3
		{
			margin:0px;
			font-size: 20px;
			line-height: 20px;
			color: #191C21;
			margin-bottom: 3px;
		}
		span
		{
			font-size: 14px;
			font-weight: normal;
			color: rgba(103, 115, 135, 0.7);
		}
	}
	.main.popup-cont
	{
		margin-bottom: 20px;
		.inp{width:calc(100% - 36px); }
	}
	.main.popup-btns
	{
		text-align: right;
		button{margin-left:20px;}
	}
	.main.popup-fade-enter-active
	{
		transition: all .05s ease;
	}
	.main.popup-fade-leave-active
	{
		transition: all .05s cubic-bezier(1, -0.53, 0.405, 1.425);
	}
	.main.popup-fade-enter, .popup-fade-leave-to
	{
		transform: translateY(-5px);
	}
</style>