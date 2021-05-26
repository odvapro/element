<template>
	<div v-click-outside=closeSidebar class="sidebar-wrapper">
		<router-link to="/" class="sidebar-logo-wrapper">
			<svg width="125" height="25">
				<use xlink:href="#logo"></use>
			</svg>
		</router-link>
		<div class="sidebar-options">
			<ul class="sidebar-options-list">
				<li class="sidebar__user" v-if="$store.state.users.authUser">
					<a href="javascript:void(0)">
						<div class="sidebar__user-img-wrapper">
							<img :src="$store.state.users.authUser.avatar" alt="">
						</div>
						<span>{{ $store.state.users.authUser.name }}</span>
					</a>
					<div class="sidebar__user-logout" @click="logOut()">{{$t('log_out')}}</div>
				</li>
				<li :class="{active: getActiveMenuItem == 'update'}"><router-link to="/update/">{{$t('update')}}</router-link></li>
				<li :class="{active: getActiveMenuItem == 'settings'}"><router-link to="/settings/">{{$t('sidebar.settings_and_users')}}</router-link></li>
			</ul>
		</div>
		<div class="sidebar-tables-wrapper">
			<div class="sidebar-table-head" v-if="extensionsLinks.length" >{{$t('sidebar.extensions')}}</div>
			<ul class="sidebar-tables-list">
				<li v-for="extLink in extensionsLinks">
					<a
						href="javascript:void(0)"
						@click="selectExtensionPage(extLink)"
						:class="{active: extLink.active}"
					>
						<div class="sidebar-points">
							<svg>
								<use xlink:href="#points"></use>
							</svg>
						</div>
						<div class="sidebar-tableicon-wrapper">
							<svg width="14" height="13">
								<use xlink:href="#tableicon"></use>
							</svg>
						</div>
						<div class="sidebar-name-wrapper">
							<div class="sidebar-overide-table-name">{{extLink.name}}</div>
						</div>
					</a>
				</li>
			</ul>
			<div class="sidebar-table-head" v-if="tables.length > 0">{{$t('tables')}}</div>
			<ul class="sidebar-tables-list">
				<li v-for="table in tables" v-if="table.visible">
					<a
						@click="selectTable(table)"
						href="javascript:void(0)"
						:class="{active: table.code == getActiveTable}"
					>
						<div class="sidebar-points">
							<svg>
								<use xlink:href="#points"></use>
							</svg>
						</div>
						<div class="sidebar-tableicon-wrapper">
							<div
								v-if="table.tviews[0] && table.tviews[0].settings && table.tviews[0].settings.emoji"
								class="sidebar-tableicon-wrapper__emoji"
							>{{ table.tviews[0].settings.emoji }}</div>
							<svg v-else width="14" height="13">
								<use xlink:href="#tableicon"></use>
							</svg>
						</div>
						<div class="sidebar-name-wrapper">
							<div class="sidebar-overide-table-name">{{table.name}}</div>
							<div class="sidebar-real-table-name">{{table.code}}</div>
						</div>
					</a>
				</li>
			</ul>
		</div>
		<div class="sidebar-footer">
			<a href="https://odva.pro" target="_blank" class="sidebar-develop-wrapper">
				<span class="sidebar-develop-label">{{$t('developed_by')}}</span>
				<div class="sidebar-develop-img">
					<svg width="38" height="31" fill="none">
						<use xlink:href="#logo-dev"></use>
					</svg>
				</div>
			</a>
		</div>
		<div class="sidebar_drug"></div>
	</div>
</template>
<script>
	import { mapGetters } from 'vuex';

	export default
	{
		data()
		{
			return {
				extensionsLinks:[]
			}
		},
		watch:
		{
			$route (to, from)
			{
				this.updateExtensionLinks();
			}
		},
		computed:
		{
			...mapGetters([
				'getExtensionsLinks',
			]),
			/**
			 * Достать из урла активную страницу
			 */
			getActiveMenuItem()
			{
				return this.$route.name;
			},
			/**
			 * Достать из урла активную таблицу
			 */
			getActiveTable()
			{
				return this.$route.params.tableCode;
			},
			/**
			 * Достать список таблиц
			 */
			tables()
			{
				return this.$store.state.tables.tables;
			},
		},
		methods:
		{
			closeSidebar()
			{
				this.$emit('closeSidebar');
			},
			/**
			 * Выход из учетной записи
			 */
			async logOut()
			{
				let result = await this.$axios.post('/auth/logOut/');

				if (!result.data.success)
					return false;

				window.location.href = '/element';
			},

			/**
			 * Достать содержимое таблицы
			 */
			async selectTable(table)
			{
				// определить основное отображение
				// сормировать url
				// перейти на этот url
				let tview = false;
				for (let cTview of table.tviews)
				{
					if(cTview.default != 1)
						continue;

					tview = cTview;
					break;
				}
				// table/<table code>/tview/<tview id>/page/<page num>/
				let url = `/table/${table.code}/tview/${tview.id}/page/1/`;
				this.$router.push(url);
				this.updateExtensionLinks();
			},

			/**
			 * Runs on select extension page
			 */
			selectExtensionPage(extLink)
			{
				this.updateExtensionLinks();
				extLink.active = true;
				this.$router.push(extLink.link);
			},

			/**
			 * Refresh extensions links active state
			 */
			updateExtensionLinks()
			{
				let fullPath = this.$route.fullPath;
				this.extensionsLinks.map(function(linkObj)
				{
					linkObj.active = false;
					if(linkObj.link == fullPath)
						linkObj.active = true;
					return linkObj;
				});
			}
		},
		async mounted()
		{
			if (!this.getExtensionsLinks || !this.getExtensionsLinks.length)
				await this.$store.dispatch('setExtensionsLinks');

			this.extensionsLinks = JSON.parse(JSON.stringify(this.getExtensionsLinks));
			this.updateExtensionLinks();
		}
	}
</script>
<style lang="scss">
	.sidebar__user-logout
	{
		font-size: 10px;
		color: rgba(25, 28, 33, 0.7);
		display: none;
	}
	.sidebar_drug
	{
		width: 4px;
		height: 100%;
		position: absolute;
		top: 0;
		right: 0px;
		cursor: col-resize;
		transition: all 0.3s;
		&:hover
		{
			background-color: #e6e6e6;
		}
		@media (max-width: 768px) { display: none; }
	}
	.sidebar-logo-wrapper
	{
		padding: 20px 20px 0;
		display: block;
	}
	.sidebar-wrapper
	{
		background: rgba(103, 115, 135, 0.1);
		height: 100vh;
		position: sticky;
		top: 0px;
		overflow: auto;
		display: flex;
		flex-direction: column;
	}
	.sidebar-options-list
	{
		li
		{
			font-family: $rMedium;
			font-size: 14px;
			color: #677387;
			line-height: 16px;
			cursor: pointer;
			padding-right: 15px;
			a
			{
				padding: 10px 0 10px 20px;
				text-decoration: none;
				color: inherit;
				display: block;
			}
			&.active, &:hover
			{
				background-color: rgba(103, 115, 135, 0.1);
			}
		}
		.sidebar__user
		{
			display: flex;
			align-items: center;
			justify-content: space-between;
			a
			{
				display: flex;
				align-items: center;
				width: 75%;
			}
			img
			{
				width: 100%;
				height: 100%;
				object-fit: contain;
			}
			span
			{
				display: block;
				overflow: hidden;
				text-overflow: ellipsis;
			}
			&:hover
			{
				.sidebar__user-logout
				{
					display: block;
				}
			}
		}
	}
	.sidebar__user-img-wrapper
	{
		width: 15px;
		height: 15px;
		min-width: 15px;
		margin-right: 7px;
		border-radius: 50%;
		overflow: hidden;
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
		li a
		{
			display: flex;
			text-decoration: none;
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
			&:hover, &.active
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
	.sidebar-tableicon-wrapper
	{
		cursor: pointer;
		margin-right: 13px;
		line-height: 1em;
		svg
		{
			width: 14px;
			height: 14px;
		}
	}
	.sidebar-tableicon-wrapper__emoji
	{
		font-size: 20px;
		line-height: 26px;
		margin-right: -4px;
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
		padding: 20px 0;
		position: sticky;
		bottom: 0px;
		width: 100%;
		background:#F0F1F3;
	}
	.sidebar-tables-wrapper{flex-grow: 1;}
	@media (max-width: 768px)
	{
		.sidebar-wrapper { min-width: 320px; }
	}
</style>
