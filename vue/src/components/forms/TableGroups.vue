<template>
	<div class="table-groups">
		<div v-for="group in tableGroups" class="table-groups__line">
			<div class="table-groups__name">
				<span>{{group.name}}</span>
				<em>{{$t('members', {count:group.members.length})}}</em>
			</div>
			<div class="table-groups__select">
				<Select :defaultText="group.access_str">
					<SelectOption
						v-for="(select, selectInd) of accessOptions"
						@click.native="setGroupAccess(group.id, select.strValue)"
						:key="selectInd"
					>{{select.title}}</SelectOption>
				</Select>
			</div>
		</div>
		<div class="table-groups__buttons">
			<button class="el-gbtn">{{$t('disableAccess')}}</button>
			<button class="el-btn">{{$t('addGroup')}}</button>
		</div>
	</div>
</template>
<script>
	export default
	{
		props: ['access','table'],
		data()
		{
			return {
				tableGroups: [],
			}
		},
		computed:
		{
			accessOptions()
			{
				return this.$store.state.groups.accessOptions;
			}
		},
		methods:
		{
			async getAccessOptions()
			{
				if (!this.$store.state.groups.accessOptions.length)
					await this.$store.dispatch('getAccessOptions');
			},
			async setGroupAccess(groupId, accessStr)
			{
				await this.$store.dispatch('setGroupAccess', {groupId, accessStr, tableName: this.table});
			},
			/**
			 * инициализация заданных доступов
			 */
			async initTableGroups()
			{
				if (!this.$store.state.groups.groups.length)
					await this.$store.dispatch('getGroups');

				let tableGroups = [];
				for (let accessInfo of this.access)
				{
					// формируется группа с доп свойствами - access и access_str
					let newTableGroup = {};
					for (let group of this.$store.state.groups.groups)
					{
						if (group.id !== accessInfo.group_id)
							continue;
						newTableGroup = JSON.parse(JSON.stringify(group));
						newTableGroup.access    = +accessInfo.access;
						newTableGroup.access_str = this.getAccessStr(newTableGroup.access);
					}
					tableGroups.push(newTableGroup);
				}
				Vue.set(this, 'tableGroups', tableGroups);
			},
			/**
			 * находит title из accessOptions по значению доступа
			 */
			getAccessStr(access)
			{
				let accessInfo = this.accessOptions.find(select=>
				{
					return select.value === access;
				});
				if (!accessInfo)
					return 'unsetted';
				else
					return accessInfo.title;
			},
		},
		async mounted()
		{
			await this.getAccessOptions();
			this.initTableGroups();
		}
	}
</script>
<style lang="scss">
	.table-groups
	{
		position: absolute;
		background: #fff;
		top:40px;
		width:320px;
		z-index: 10;
		box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
		border-radius: 2px;
		border: 1px solid rgba(103, 115, 135, 0.1);
	}
	.table-groups__line
	{
		padding:15px;
		border-bottom: 1px solid rgba(103, 115, 135, 0.1);
		display: flex;
		justify-content: space-between;
		align-items: center;
		&:hover{background: rgba(103, 115, 135, 0.1);}
	}
	.table-groups__name
	{
		span
		{
			display: block;
			font-size: 12px;
		}
		em
		{
			font-style: normal;
			color:rgba(103, 115, 135, 0.4);
		}
	}
	.table-groups__buttons
	{
		padding:15px;
		display: flex;
		.el-gbtn{margin-right: 12px; width:145px;}
		.el-btn{flex-grow: 1;}
	}
	.table-groups__select .select__trigger
	{
		border:0px;
		background: none;
		&:hover{background: rgba(103, 115, 135, 0.1);}
	}
</style>