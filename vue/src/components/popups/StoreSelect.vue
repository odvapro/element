<template>
	<Popup class="select-popup__add" :visible.sync="isPopupVisible">
		<input v-model="searchText" class="select-popup__add-search" type="text" :placeholder="$t('search')+'...'">
		<div class="select-popup__item-line" v-for="item in items" @click="selectItem(item)">
			<img :src="item.avatar" alt="" v-if="item.avatar">
			{{ item.name }}
		</div>
	</Popup>
</template>
<script>
	import qs from 'qs';
	export default
	{
		props: ['visible', 'settings', 'disableIf', 'disableIfNot'],
		data()
		{
			return {
				searchText:'',
			};
		},
		computed:
		{
			isPopupVisible:
			{
				get()
				{
					return this.visible
				},
				set(val)
				{
					this.$emit('update:visible', val);
				}
			},
			searched()
			{
				if (this.settings.searchArr)
					return this.settings.searchArr;

				let searched = this;
				for (let property of this.settings.searchStr.split('.'))
					searched = searched[property];
				return searched;
			},
			items()
			{
				let itemsCount = 0;
				return this.searched.filter(item => {
					if(itemsCount > 10
						|| typeof item.name == 'undefined'
						|| (this.disableIf && item[this.disableIf])
						|| (this.disableIfNot && !item[this.disableIfNot])) return false;

					const searchText = this.searchText.toLowerCase();
					const name       = item.name.toLowerCase();
					const showItem   = name.indexOf(searchText) !== -1;

					if(!showItem)
						return false;

					itemsCount++;
					return true;
				});
			},
		},
		methods:
		{
			selectItem(item)
			{
				this.$emit('selectItem',item);
				this.$emit('update:visible', false);
			}
		}
	}
</script>
<style lang="scss">
	.select-popup__add .popup-close{display: none;}
	.select-popup__add-search
	{
		height: 43px;
		background: rgba(103, 115, 135, 0.1);
		border:0px;
		border-radius: 3px;
		width:100%;
		padding-left: 24px;
		font-size: 11px;
		margin-bottom: 15px;
		&::placeholder{color: rgba(103, 115, 135, 0.7);}
	}
	.select-popup__item-line
	{
		display: flex;
		align-items: center;
		font-size:11px;
		color: #191C21;
		height: 30px;
		cursor: pointer;
		margin-left: -20px;
		margin-right: -20px;
		padding-left: 20px;
		img
		{
			width:15px;
			height: 15px;
			object-fit: cover;
			border-radius: 50%;
			margin-right: 7px;
		}
		&:hover{background: rgba(103, 115, 135, 0.1);}
	}
</style>
