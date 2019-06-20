<template>
	<div class="list" v-click-outside="closePopup">
		<div class="list__shown" @click="openPopup()">
			<div class="list__item" v-for="selectedItem in selectedItems">
				{{ selectedItem.value }}
			</div>
			<span v-if="!selectedItems.length" class="el-empty">Empty</span>
		</div>
		<div class="list__search" v-if="showPopup">
			<div class="list__search-popup-head">
				<input
					ref="searchInput"
					v-if="!selectedItems.length"
					class="el-inp-noborder"
					type="text"
					placeholder="Search for an option..."
					v-model="searchText"
				/>
				<template v-else>
					<div
						class="list__search-item"
						v-for="(selectedItem, selectedItemIndex) in selectedItems"
					>
					{{ selectedItem.value }}
					<svg width="9" height="9" @click.stop="remove(selectedItemIndex)">
						<use xlink:href="#plus-white"></use>
					</svg>
					</div>
				</template>
			</div>
			<div
				class="list__search-popup-item"
				v-for="listItem in itemsList"
				@click="changeData(listItem)"
			>
				<div class="list__search-item">
					{{listItem.value}}
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	/**
	 * Принимает параметры
	 * - selected = массив кодов выбранных опций [keyCode1,keyCode2...]
	 * - list = массив для выбора [ {key:'exampleKey',value:'exampleValue'},
	 * 								{key:'exampleKey2',value:'exampleValue2'} ]
	 */
	export default
	{
		props: ['selected', 'list'],
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				showPopup: false,
				searchText:'',
				localSelected:[]
			}
		},
		computed:
		{
			/**
			 * Возврощает список выбранных элементов
			 * [{key:<key code>,value:<value>}]
			 */
			selectedItems()
			{
				if(this.localSelected.length == 0)
					return [];
				return this.list.filter(listItem=>{
					if(this.localSelected.indexOf(listItem.key) !== -1)
						return true;
				});
			},

			/**
			 * Выдаем отфильтрованный список опций
			 */
			itemsList()
			{
				return this.list.filter(listItem=>{
					if(listItem.value.indexOf(this.searchText) !== -1)
						return true;
				});
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
				this.searchText = '';
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
			 * Изменить тип поля
			 */
			async changeData(itemValue)
			{
				this.localSelected = [itemValue.key];
				this.$emit('onChange', {
					value     : this.localSelected,
					settings  : this.fieldSettings
				});
				this.closePopup();
			},

			/**
			 * Удаляет желемент из списка
			 */
			remove(itemIndex)
			{
				this.localSelected.splice(itemIndex,1);
				this.$emit('onChange', {
					value     : this.localSelected,
					settings  : this.fieldSettings
				});
				this.closePopup();
			}
		},
		mounted()
		{
			this.localSelected = this.selected
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
	}
	.list__item
	{
		display: inline-block;
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
		height: 30px;
		display: flex;
		align-items: center;
		padding: 0 9px;
		font-size: 10px;
		background-color: rgba(103, 115, 135, 0.05);
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
		top: -2px;
		background: white;
		z-index: 2;
		left: -2px;
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
		svg
		{
			position: relative;
			top:1px;
			stroke:#677387;
			transform: rotate(45deg);
		}
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