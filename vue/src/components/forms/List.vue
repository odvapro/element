<template>
	<div class="list" v-click-outside="closePopup">
		<div class="list__shown" @click="openPopup()">
			<span v-if="!hasSelectedSlot" class="el-empty">{{ placeholder }}</span>
			<slot name="selected"></slot>
		</div>
		<div
			class="list__search"
			v-if="showPopup"
			@keydown.esc="closePopup"
			tabindex="1"
		>
			<div class="list__search-popup-head">
				<slot name="selected"></slot>
				<input
					ref="searchInput"
					class="el-inp-noborder"
					type="text"
					v-model="localSearchText"
				/>
			</div>
			<div class="list__options">
				<slot></slot>
			</div>
		</div>
	</div>
</template>
<script>
	export default
	{
		props:{
			searchText: {type: String},
			settings: {type: Object},
			multiple: {
				type: Boolean,
				default: false
			}
		},
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				showPopup: false,
				localSearchText:'',
				localSelected:this.selected,
				placeholder:this.$t('empty')
			}
		},
		watch:
		{
			localSearchText(value)
			{
				this.$emit('update:searchText',this.localSearchText);
			}
		},
		computed:
		{
			/**
			 * Проверка есть слот или нет
			 */
			hasDefaultSlot()
			{
				return !!this.$slots.default
			},

			/**
			 * Проверка есть слот или нет
			 */
			hasSelectedSlot()
			{
				return !!this.$slots['selected']
			}
		},
		methods:
		{
			/**
			 * Открыть попап
			 */
			openPopup()
			{
				this.showPopup = true;
				this.localSearchText = '';
				this.$emit('onopen');
				this.$nextTick(()=>{
					if(typeof this.$refs.searchInput != 'undefined')
						this.$refs.searchInput.focus();
				});
			},

			/**
			 * Закрыть попап
			 */
			closePopup()
			{
				this.showPopup = false;
			},

			/**
			 * Срабатывает при выборе пункт
			 */
			selectEvent()
			{
				if(!this.multiple)
					this.closePopup();

				this.$refs.searchInput.focus();
			},

			/**
			 * Срабатывает при удалении выбранной опции
			 */
			removeEvent()
			{
				this.$refs.searchInput.focus();
			}
		},
		mounted()
		{
			if(typeof this.settings == 'undefined')
				return true;
			if(typeof this.settings.placeholder != 'undefined')
				this.placeholder = this.settings.placeholder;
		}
	}
</script>
<style lang="scss">
	.list
	{
		width:100%;
		height: 100%;
	}
	.list__shown
	{
		width:100%;
		height: 100%;
		display: flex;
		align-items: center;
		justify-content: flex-start;
		overflow: hidden;
	}
	.list__search-popup-head
	{
		display: flex;
		flex-wrap:wrap;
		align-items: center;
		padding: 8px 9px;
		font-size: 10px;
		background-color: rgba(103, 115, 135, 0.05);
		color: rgba(25, 28, 33, 0.4);
		border-bottom: 1px solid rgba(103, 115, 135, 0.1);
		row-gap: 4px;
	}
	.list__search
	{
		box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
		width: 250px;
		border: 1px solid rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		background: white;
		position: absolute;
		top: -5px;
		background: white;
		z-index: 2;
		left: -5px;
	}
	.list__options
	{
		max-height: 190px;
		overflow: auto;
		margin:5px 0;
		.list-option{padding:5px 0 5px 10px;}
		.list-option:hover{background: rgba(103, 115, 135, 0.1);}
	}
	.list__search-popup-head .list-option__remove{display: inline-block;}
	.list__search-popup-head .list-option span{padding-right: 18px;}
</style>
