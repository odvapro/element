<template>
	<div class="list__table-select" @click="togglePopup()">
		<div class="list__item-wrapper">
			<div class="list__head-item">{{fieldValue}}</div>
		</div>
		<div class="list__search" v-if="showPopup" v-click-outside="closePopup">
			<div class="list__search-popup-head">
				<div class="list__search-item">
					{{fieldValue}}
				</div>
			</div>
			<div class="list__search-popup-item" v-for="listItem in fieldSettings.values" @click="selectItem(listItem)">
				<div class="list__search-icon">
					<svg width="6" height="5">
						<use xlink:href="#lines"></use>
					</svg>
				</div>
				<div class="list__search-item">
					{{listItem.name}}
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default
	{
		props: ['params'],
		/**
		 * Глобальные переменные странциы
		 */
		data()
		{
			return {
				showPopup: false,
				fieldValue: '',
				fieldSettings: {}
			}
		},
		methods:
		{
			/**
			 * Открыть/Закрыть попап
			 */
			togglePopup()
			{
				this.showPopup = !this.showPopup;
			},
			/**
			 * Закрыть попап
			 */
			closePopup()
			{
				this.showPopup = false;
			},
			/**
			 * Выбрать поле из списка
			 */
			selectItem(data)
			{
				this.fieldValue = data.name;
				this.$emit('onChange', {
					data   : data,
					column : this.fieldSettings.fieldCode,
					table  : this.fieldSettings.tableCode
				});
			}
		},
		/**
		 * Хук при загрузке компонента
		 */
		mounted()
		{
			this.fieldValue = this.params.value;
			this.fieldSettings = this.params.settings;
		}
	}
</script>
<style lang="scss">
.list__field__position__revative
{
	position: relative;
}
.list__head-item
{
	padding: 4px 8px;
	background-color: rgba(124, 119, 145, 0.1);
	border-radius: 2px;
	font-size: 10px;
	margin-right: 2px;
	color: #7C7791;
	position: relative;
	cursor: pointer;
}
.list__search-popup-head
{
	height: 49px;
	display: flex;
	align-items: center;
	padding: 0 9px;
	font-size: 10px;
	background-color: rgba(103, 115, 135, 0.1);
	color: rgba(25, 28, 33, 0.4);
	border-bottom: 1px solid rgba(103, 115, 135, 0.1);
}
.list__search
{
	box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
	width: 193px;
	border: 1px solid rgba(103, 115, 135, 0.1);
	border-radius: 2px;
	background: white;
	position: absolute;
	top: -1px;
	background: white;
	z-index: 2;
	left: -1px;
}
.list__search-icon
{
	width: 6px;
	height: 14px;
	display: flex;
	align-items: center;
	margin-right: 8px;
	img
	{
		width: 100%;
		height: 100%;
		object-fit: contain;
	}
}
.list__search-item
{
	padding: 4px 8px;
	background-color: rgba(124, 119, 145, 0.1);
	border-radius: 2px;
	font-size: 10px;
	margin-right: 2px;
	color: #7C7791;
	position: relative;
}
.list__search-popup-item
{
	display: flex;
	padding: 0 9px;
	align-items: center;
	height: 30px;
	cursor: pointer;
	&:hover
	{
		background-color: rgba(103, 115, 135, 0.1);
	}
}
</style>