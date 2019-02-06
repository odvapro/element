<template>
	<div class="sidebar-wrapper">
		<div class="sidebar-logo-wrapper">
			<img src="/images/logo.svg" alt="">
		</div>
		<div class="sidebar-options">
			<ul class="sidebar-options-list">
				<li>Quiq Find</li>
				<li>Update</li>
				<li>Settings & Users</li>
			</ul>
		</div>
		<div class="sidebar-tables-wrapper">
			<div class="sidebar-table-head">Tables</div>
			<ul class="sidebar-tables-list">
				<li v-for="item in tablesList"
					:class="{active: item.code == $store.state.tables.tableName.real}"
					@click="getTableContent(item)"
				>
					<div class="sidebar-points">
						<img src="/images/points.svg" alt="">
					</div>
					<div class="sidebar-arrow">
						<img src="/images/arrow.svg" alt="">
					</div>
					<div class="sidebar-tableicon-wrapper">
						<img src="/images/tableicon.svg" alt="">
					</div>
					<div class="sidebar-name-wrapper">
						<div class="sidebar-overide-table-name">{{item.code}}</div>
						<div class="sidebar-real-table-name">{{item.code}}</div>
					</div>
				</li>
			</ul>
		</div>
		<div class="sidebar-footer">
			<a href="#" class="sidebar-develop-wrapper">
				<span class="sidebar-develop-label">Developed by</span>
				<div class="sidebar-develop-img">
					<img src="/images/logo-dev.svg" alt="">
				</div>
			</a>
		</div>
		<div class="drug"></div>
	</div>
</template>
<script>
	export default
	{
		computed:
		{
			/**
			 * Достать список таблиц
			 */
			tablesList()
			{
				return this.$store.state.tables.tablesList;
			},
			/**
			 * Достать название таблицы
			 */
			tableName()
			{
				return this.$store.state.tables.tableName;
			}
		},
		methods:
		{
			/**
			 * Достать содержимое таблицы
			 */
			async getTableContent(tableCol)
			{
				await this.$store.dispatch('getColumns', tableCol.code);
				await this.$store.commit('setTableInfo', tableCol);
				await this.$store.dispatch('select', {select: { from: tableCol.code }});
			}
		},
		async mounted()
		{
			await this.$store.dispatch('getTables');
			await this.getTableContent(this.tablesList[0]);
		}
	}
</script>
<style lang="scss">
	.drug
	{
		width: 4px;
		height: 100%;
		position: absolute;
		top: 0;
		right: -2px;
		cursor: col-resize;
		transition: all 0.3s;
		&:hover
		{
			background-color: #e6e6e6;
		}
	}
	.sidebar-logo-wrapper
	{
		padding: 20px 20px 0;
	}
	.sidebar-wrapper
	{
		background: rgba(103, 115, 135, 0.1);
		position: relative;
		padding-bottom: 112px;
	}
	.sidebar-options-list
	{
		li
		{
			padding: 9px 0 9px 20px;
			font-family: $rMedium;
			font-size: 14px;
			color: #677387;
			line-height: 16px;
			cursor: pointer;
			&.active, &:hover
			{
				background-color: rgba(103, 115, 135, 0.1);
			}
		}
	}
	.sidebar-options
	{
		padding: 13px 0;
	}
	.sidebar-table-head
	{
		padding: 5px 20px;
		font-family: $rLight;
		font-size: 14px;
		line-height: 16px;
		text-transform: uppercase;
	}
	.sidebar-tables-list
	{
		padding: 11px 0;
		li
		{
			display: flex;
			align-items: center;
			height: 40px;
			padding-left: 23px;
			position: relative;
			cursor: pointer;
			.sidebar-points
			{
				display: none;
				position: absolute;
				top: 0;
				right: 0;
				height: 100%;
				width: 19px;
				padding: 0 17px;
				align-items: center;
				img
				{
					width: 100%;
					height: 2px;
				}
			}
			&:hover
			{
				background-color: rgba(103, 115, 135, 0.1);
				.sidebar-points
				{
					display: flex;
				}
			}
			&.active
			{
				background-color: rgba(103, 115, 135, 0.1);
			}
		}
	}
	.sidebar-name-wrapper
	{
		cursor: pointer;
	}
	.sidebar-overide-table-name
	{
		font-family: $rMedium;
		font-size: 14px;
		line-height: 16px;
		color: #191C21;
	}
	.sidebar-real-table-name
	{
		color: rgba(103, 115, 135, 0.4);
		font-size: 10px;
	}
	.sidebar-arrow
	{
		margin-right: 16px;
		width: 7px;
		height: 14px;
		cursor: pointer;
		img
		{
			width: 100%;
			height: 100%;
		}
	}
	.sidebar-tableicon-wrapper
	{
		width: 14px;
		height: 14px;
		cursor: pointer;
		margin-right: 13px;
		img
		{
			width: 100%;
			height: 100%;
		}
	}
	.sidebar-develop-wrapper
	{
		display: flex;
		align-items: center;
		width: 154px;
		height: 32px;
		margin: 0 auto;
		text-decoration: none;
		justify-content: space-between;
	}
	.sidebar-develop-label
	{
		font-family: $mainFont;
		font-size: 14px;
		color: #677387;
	}
	.sidebar-footer
	{
		padding: 40px 0;
		position: absolute;
		bottom: 0;
		width: 100%;
	}
</style>