<template>
	<div class="table-groups">
		<div v-for="group in tableGroups" v-if="group" class="table-groups__line">
			<div class="table-groups__name">
				<span>{{group.name}}</span>
				<em>{{$t('members', {count:group.members.length})}}</em>
			</div>
			<div class="table-groups__select">
				<Select
					:defaultText="group.access_str"
					:disabled="group.is_admin"
				>
					<SelectOption
						v-for="(select, selectInd) of accessOptions"
						@click.native="setGroupAccess(group.id, select.strValue)"
						:key="selectInd"
					>{{select.title}}</SelectOption>
				</Select>
			</div>
		</div>
		<div class="table-groups__buttons">
			<button @click="$emit('disableTableAccess', table)" class="el-gbtn">{{$t('disableAccess')}}</button>
			<button @click="$emit('showTableGroupsAdding')" class="el-btn">{{$t('addGroup')}}</button>
		</div>
	</div>
</template>
<script>
	import { mapState } from 'vuex';

	export default
	{
		props: ['access','table'],
		data()
		{
			return {
				tableGroups: [],
			};
		},
		computed:
		{
			...mapState({
				groups       : state => state.groups.groups,
				accessOptions: state => state.groups.accessOptions,
			}),
		},
		async mounted()
		{
			await this.getAccessOptions();
			await this.initTableGroups();
		},
		methods:
		{
			async getAccessOptions()
			{
				if (!this.accessOptions || !this.accessOptions.length)
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
				if (!this.groups || !this.groups.length)
					await this.$store.dispatch('getGroups');

				const groups = [];
				for	(let group of this.groups)
				{
					for (let accessInfo of this.access)
					{
						if (+accessInfo.group_id === +group.id)
							groups.push({
								...group,
								access: +accessInfo.access,
								access_str: this.getAccessStr(+accessInfo.access),
								id: +group.id,
							});
					}
				}

				this.$set(this, 'tableGroups', groups);
			},
			/**
			 * находит title из accessOptions по значению доступа
			 */
			getAccessStr(access)
			{
				let accessInfo = this.accessOptions.find(select => select.value === access );
				if (!accessInfo)
					return 'unsetted';
				else
					return accessInfo.title;
			},
		},
	};
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
		&.disabled:hover { background: none; }
	}
</style>
