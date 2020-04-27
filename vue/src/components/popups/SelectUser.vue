<template>
	<Popup class="select-user__add-popup" :visible.sync="isPopupVisible">
		<input v-model="searchText" class="select-user__add-search" type="text" placeholder="Search for person">
		<div class="select-user__user-line" v-for="user in users" @click="selectUser(user)">
			<img :src="user.avatar" alt="">
			{{ user.name }}
		</div>
	</Popup>
</template>
<script>
	import qs from 'qs';
	export default
	{
		props: ['visible'],
		data()
		{
			return {
				searchText:''
			}
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
			users()
			{
				let usersCount = 0;
				return this.$store.state.settings.users.filter(user => {
					if(usersCount > 10 || typeof user.name == 'undefined') return false;

					const searchText = this.searchText.toLowerCase();
					const name       = user.name.toLowerCase();
					const showUser   = name.indexOf(searchText) !== -1;

					if(!showUser)
						return false;

					usersCount++;
					return true;
				});
			}
		},
		methods:
		{
			selectUser(user)
			{
				this.$emit('selectUser',user);
				this.$emit('update:visible', false);
			}
		}
	}
</script>
<style lang="scss">
	.select-user__add-popup .popup-close{display: none;}
	.select-user__add-search
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
	.select-user__user-line
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