<template>
	<div class="list" v-click-outside="closePopup">
		<div class="list__shown" @click="openPopup()">
			<div class="list__item" v-if="selectedItem">
				{{ selectedItem }}
			</div>
			<span v-else class="el-empty">Empty</span>
		</div>
		<div class="list__search" v-if="showPopup">
			<div class="list__search-popup-head">
				<input
					ref="searchInput"
					v-if="!selectedItem"
					class="el-inp-noborder"
					type="text"
					placeholder="Search for an option..."
					v-model="searchText"
				/>
				<template v-else>
					<div class="list__search-item"> {{ selectedItem }} </div>
				</template>
			</div>
			<div
				class="list__search-popup-item"
				v-for="listItem in list"
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
	export default
	{
		props: ['selectVal', 'list'],
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				showPopup: false,
				selectedItem: '',
				listValues: [],
				searchText:''
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
				this.$emit('onChange', {value: itemValue.key});
				this.selectedItem = itemValue.value;
				this.closePopup();
			}
		},
		/**
		 * Хук при загрузке страницы
		 */
		mounted()
		{
			this.listValues = this.list;
			this.selectedItem = this.selectVal;
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