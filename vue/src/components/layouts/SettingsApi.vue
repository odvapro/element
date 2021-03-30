<template>
	<div class="el-api">
		<div class="el-api-tokens">
			<h2 class="el-api__title el-api-tokens__title">Tokens</h2>
			<div
				class="el-api-token"
				v-for="(token, tokenInd) in tokens">
				<div class="el-api-token__info">
					<div class="el-api-token-value">{{token.value}}</div>
					<div class="el-api-token-date">Generated {{token.date}}</div>
				</div>
				<div class="el-api-token__actions">
					<Select
						v-if="groups && groups.length"
						:defaultText="token.group_name"
						class="select--simple"
					>
						<SelectOption
							v-for="group in groups"
							@click.native="changeTokenGroup(token.id, group.id)"
							:key="group.code"
						>{{ group.name }}
					</SelectOption>
					</Select>
					<div @click="removeToken(token.id)" class="el-api-token-remove">{{$t('remove')}}</div>
				</div>
			</div>
			<div @click="createToken" class="el-api-tokens-add">
				<span class="el-api-tokens-add__icon"><svg width="9" height="9" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg"><line x1="0.5" y1="4.5" x2="8.5" y2="4.5" stroke="#677387" stroke-opacity="0.4" stroke-linecap="round"/><line x1="4.5" y1="8.5" x2="4.5" y2="0.5" stroke="#C2C7CF" stroke-linecap="round"/></svg></span>
				Generate Token
			</div>
		</div>
		<div class="el-api-docs">
			<div class="el-api-docs__head">
				<h2 class="el-api__title el-api__title--no-border">API Docs</h2>
				<Select
					:defaultText="selectedTable.code"
					class="select--simple"
				>
					<SelectOption
						v-for="table in tables"
						@click.native="selectTableForApi(table)"
						:key="table.code"
					>{{ table.code }}</SelectOption>
				</Select>
			</div>
			<div
				class="el-api-doc"
				v-for="(doc, docType) in docs"
			>
				<div class="el-api-doc-header">
					<div class="el-api-doc__title">{{docType}}</div>
					<div class="el-api-doc-tabs">
						<div
							@click="changeDocBlockTab(docType, 'code')"
							class="el-api-doc-tab"
							:class="{'active': doc.tab === 'code'}"
						>Code</div>
						<div
							@click="changeDocBlockTab(docType, 'response')"
							class="el-api-doc-tab"
							:class="{'active': doc.tab === 'response'}"
						>Response</div>
					</div>
				</div>
				<div class="el-api-code">
					<div @click="copyText(doc[doc.tab])" class="el-api-code-copy">copy</div>
					<div v-highlight v-if="doc.tab && doc[doc.tab]">
						<pre><code class="bash">{{doc[doc.tab]}}</code></pre>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import { mapState } from 'vuex';

	export default
	{
		data()
		{
			return {
				docs: {
					get   :{tab: 'code'},
					insert:{tab: 'code'},
					delete:{tab: 'code'},
					update:{tab: 'code'},
				},
				selectedTable: {},
			};
		},
		computed:
		{
			...mapState({
				tables: state => state.tables.tables,
				groups: state => state.groups.groups,
				tokens: state => state.groups.tokens,
				apiDocs: state => state.groups.apiDocs,
			}),
		},
		methods:
		{
			/**
			 * сменить группу с токена
			 */
			async changeTokenGroup(tokenId, groupId)
			{
				await this.$store.dispatch('changeToken', {tokenId, groupId});
			},
			/**
			 * запрос на удаление токена
			 */
			async removeToken(tokenId)
			{
				await this.$store.dispatch('removeToken', {tokenId});
			},
			/**
			 * запрос на создание нового токена для групп
			 */
			async createToken()
			{
				await this.$store.dispatch('createToken', {groupId: this.groups[0].id});
			},
			/**
			 * переключить таб на блоке с кодом
			 */
			async changeDocBlockTab(docType, activeTab)
			{
				if (!this.docs[docType])
					return;
				this.docs[docType].tab = '';

				// это нужно, чтобы див перерисовывался и highlight.js заново отработал
				await setTimeout(()=>{},1);
				this.docs[docType].tab = activeTab;
			},
			async selectTableForApi(table)
			{
				this.selectedTable = table;
				await this.setApiDocs();
			},
			async setApiDocs()
			{
				await this.$store.dispatch('getApiDocs', {table_name: this.selectedTable.code});
				for ( let [docType, doc] of Object.entries(this.apiDocs) )
				{
					this.docs[docType] = { ...this.docs[docType], ...doc };
					this.changeDocBlockTab(docType, this.docs[docType].tab);
				}
			},
			copyText(str)
			{
				const input = document.createElement('input');
				input.value = str;
				document.body.appendChild(input);
				input.select();
				document.execCommand('copy');
				document.body.removeChild(input);
			},
		},
		async mounted()
		{
			this.selectTableForApi(this.tables[0]);
			if (!this.groups.length)
				await this.$store.dispatch('getGroups');

			if (!this.tokens.length)
				await this.$store.dispatch('getTokens');
		}
	}
</script>

<style lang="scss">
	.el-api
	{
		width: 630px;
	}
	.el-api__title
	{
		font-size: 12px;
		line-height: 14px;
		font-family: $rMedium;
		padding-bottom: 7px;
		border-bottom: 1px solid rgba(103, 115, 135, 0.1);
		margin: 0;
		&--no-border { border: 0; padding-bottom: 0; }
	}
	.el-api-tokens
	{
		margin-bottom: 30px;
	}
	.el-api-tokens__title
	{
		margin-bottom: 17px;
	}
	.el-api-token
	{
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 15px;
		&:last-child
		{
			margin-bottom: 0;
		}
	}
	.el-api-token__info
	{

	}
	.el-api-token-value
	{
		font-size: 11px;
		line-height: 13px;
		margin-bottom: 4px;
	}
	.el-api-token-date
	{
		font-size: 10px;
		line-height: 12px;
		color: rgba(103, 115, 135, 0.7);
	}
	.el-api-token__actions
	{
		display: flex;
		align-items: center;
	}
	.el-api-token-remove
	{
		cursor: pointer;
		font-size: 11px;
		line-height: 13px;
		margin-left: 22px;
	}
	.el-api-tokens-add
	{
		cursor: pointer;
		font-size: 11px;
		line-height: 13px;
		color: #677387;
	}
	.el-api-tokens-add__icon
	{
		display: inline-block;
		margin-right: 7px;
		font-size: 0;
		line-height: 0;
		svg
		{
			width: 9px;
			height: 9px;
		}
	}
	.el-api-docs__head
	{
		margin-bottom: 28px;
		display: flex;
		justify-content: space-between;
		align-items: center;
		border-bottom: 1px solid rgba(103, 115, 135, 0.1);
	}
	.el-api-doc
	{
		margin-bottom: 25px;
		&:last-child
		{
			margin-bottom: 0;
		}
	}
	.el-api-doc-header
	{
		display: flex;
		justify-content: space-between;
	}
	.el-api-doc__title
	{
		font-size: 12px;
		line-height: 14px;
		margin-bottom: 6px;
		text-transform: capitalize;
		font-family: $rMedium;
	}
	.el-api-doc-tabs
	{
		display: flex;
	}
	.el-api-doc-tab
	{
		font-size: 12px;
		line-height: 14px;
		font-family: $rMedium;
		color: rgba(25, 28, 33, 0.4);
		padding: 0 3px 7px;
		margin-right: 18px;
		margin-bottom: -1px;
		cursor: pointer;
		position: relative;
		&:after
		{
			content: '';
			position: absolute;
			width: 100%;
			bottom: 0;
			left: 0;
			height: 2px;
			border-radius: 2px;
		}
		&:last-child
		{
			margin-right: 0;
		}
		&.active
		{
			color: #191C21;
			&:after
			{
				background-color: #191C21;
			}
		}
	}
	.el-api-code
	{
		position: relative;
		background-color: rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		padding: 18px;
	}
	.el-api-code-copy
	{
		font-size: 12px;
		line-height: 14px;
		color: rgba(103, 115, 135, 0.4);
		font-family: $rMedium;
		cursor: pointer;
		position: absolute;
		right: 10px;
		top: 8px;
	}
	.el-api-code-content
	{
		font-size: 12px;
		line-height: 215%;
		font-family: $rMedium;
		color: rgba(25, 28, 33, 0.7);
	}
	.el-api-code-content__row
	{
		position: relative;
		counter-increment: li;
		&:before
		{
			content: counter(li);
			color: rgba(103, 115, 135, 0.4);
		}
	}
	@media (max-width: 768px)
	{
		.el-api
		{
			min-width: calc(100vw - 46px);
			width: auto;
		}
	}
</style>
