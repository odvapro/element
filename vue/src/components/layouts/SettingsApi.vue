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
						v-if="groups.length"
						:defaultText="groups[0].name"
						class="select--simple"
					>
						<SelectOption
							v-for="group in groups"
							@click.native="selectGroup(group)"
							:key="group.code"
						>{{ group.name }}</SelectOption>
					</Select>
					<div @click="removeToken(token, tokenInd)" class="el-api-token-remove">{{$t('remove')}}</div>
				</div>
			</div>
			<div @click="addToken" class="el-api-tokens-add">
				<span class="el-api-tokens-add__icon"><svg width="9" height="9" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg"><line x1="0.5" y1="4.5" x2="8.5" y2="4.5" stroke="#677387" stroke-opacity="0.4" stroke-linecap="round"/><line x1="4.5" y1="8.5" x2="4.5" y2="0.5" stroke="#C2C7CF" stroke-linecap="round"/></svg></span>
				Generate Token
			</div>
		</div>
		<div class="el-api-docs">
			<h2 class="el-api__title el-api__title--select el-api-docs__title">API Docs
				<Select
					:defaultText="selectedTable.code"
					class="select--simple"
				>
					<SelectOption
						v-for="table in tables"
						@click.native="selectTable(table)"
						:key="table.code"
					>{{ table.code }}</SelectOption>
				</Select>
			</h2>
			<div
				class="el-api-doc"
				v-for="doc in docs"
			>
				<div class="el-api-doc-header">
					<div class="el-api-doc__title">{{doc.type}}</div>
					<div class="el-api-doc-tabs">
						<div class="el-api-doc-tab active">Code</div>
						<div class="el-api-doc-tab">Response</div>
					</div>
				</div>
				<div class="el-api-code">
					<div class="el-api-code-copy">copy</div>
					<!-- <ul class="el-api-code-content">
						<li
							class="el-api-code-content__row"
							v-for="responseStr of doc.response"
						>
							{{responseStr}}
						</li>
					</ul> -->
					<pre class="test bash" >
						<code class="">
							{{doc.response}}
						</code>
					</pre>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default
	{
		data()
		{
			return {
				tokens: [
					{ value: '4935eefddd20e1c2da613b3e64b65651', date: '12 January 2020 12:30', group: 1 },
					{ value: 'b7580864176a7061daab7cd82ed2aee7', date: '12 January 2020 12:30', group: 1 },
					{ value: 'test1', date: '12 January 2020 12:30', group: 1 },
					{ value: '2222', date: '12 January 2020 12:30', group: 1 },
					{ value: 'test3', date: '12 January 2020 12:30', group: 1 },
				],
				docs: [
					{
						type:     'Get',
						// response: ['curl https://api.stripe.com/v1/charges','-u sk_test_4eC39HqLyjWDarjtT1zdp7dc:','# The colon prevents curl from asking for a password.']
						response: `\ncurl https://api.stripe.com/v1/charges \n-d sk_test_4eC39HqLyjWDarjtT1zdp7dc:\n# The colon prevents curl from asking for a password.`
					},
					{
						type:     'Insert',
						code:     ['curl https://api.stripe.com/v1/charges','-u sk_test_4eC39HqLyjWDarjtT1zdp7dc:','# The colon prevents curl from asking for a password.'],
						response: ['curl https://api.stripe.com/v1/charges','-u sk_test_4eC39HqLyjWDarjtT1zdp7dc:','# The colon prevents curl from asking for a password.']
					},
					{
						type:     'Delete',
						code:     ['curl https://api.stripe.com/v1/charges','-u sk_test_4eC39HqLyjWDarjtT1zdp7dc:','# The colon prevents curl from asking for a password.'],
						response: ['curl https://api.stripe.com/v1/charges','-u sk_test_4eC39HqLyjWDarjtT1zdp7dc:','# The colon prevents curl from asking for a password.']
					},
					{
						type:     'Update',
						code:     ['curl https://api.stripe.com/v1/charges','-u sk_test_4eC39HqLyjWDarjtT1zdp7dc:','# The colon prevents curl from asking for a password.'],
						response: ['curl https://api.stripe.com/v1/charges','-u sk_test_4eC39HqLyjWDarjtT1zdp7dc:','# The colon prevents curl from asking for a password.']
					},
				],
				selectedTable: ''
			}
		},
		computed:
		{
			tables()
			{
				return this.$store.state.tables.tables;
			},
			groups()
			{
				return this.$store.state.groups.groups;
			},
		},
		methods:
		{
			selectTable(table)
			{
				this.selectedTable = table;
			},
			selectGroup(group)
			{

			},
			removeToken(token, index)
			{
				this.tokens.splice(index, 1);
			},
			addToken()
			{
				this.tokens.push({ value: 'b7580864176a7061daab7cd82ed2aee7', date: '12 January 2020 12:30', group: 1 },);
			}

		},
		async mounted()
		{
			console.log(this.$hljs.initHighlightingOnLoad());
			this.$hljs.highlightBlock(document.querySelector('.test'))
			this.selectTable(this.tables[0]);
			if (!this.groups.length)
				await this.$store.dispatch('getGroups');
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
		&--select
		{
			display: flex;
			justify-content: space-between;
		}
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
	.el-api-docs {
	}
	.el-api-docs__title
	{
		margin-bottom: 28px;
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
</style>