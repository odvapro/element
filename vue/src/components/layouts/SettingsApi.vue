<template>
	<div class="el-api">
		<div class="el-api-tokens">
			<h2 class="el-api__title el-api-tokens__title">Tokens</h2>
			<div
				class="el-api-token"
				v-for="token in tokens">
				<div class="el-api-token__info">
					<div class="el-api-token-value">{{token.value}}</div>
					<div class="el-api-token-date">Generated {{token.date}}</div>
				</div>
				<div class="el-api-token__actions">
					<div class="el-api-select"
						>Administrators<span class="el-api-select__arrow"><svg width="9" height="5" viewBox="0 0 9 5" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.46257 0.413952C8.35094 0.302324 8.16954 0.302324 8.05792 0.413952L4.49978 3.97907L0.934661 0.413952C0.823033 0.302324 0.641638 0.302324 0.53001 0.413952C0.418382 0.525579 0.418382 0.706975 0.53001 0.818603L4.29047 4.57907C4.34629 4.63488 4.41606 4.66279 4.4928 4.66279C4.56257 4.66279 4.63931 4.63488 4.69513 4.57907L8.45559 0.818603C8.57419 0.706975 8.5742 0.525579 8.46257 0.413952Z" fill="#677387" fill-opacity="0.4"/><path d="M8.02256 0.378596L8.02253 0.378631L4.49974 3.90832L0.970016 0.378596C0.838862 0.247442 0.625809 0.247442 0.494655 0.378596C0.363501 0.50975 0.363501 0.722804 0.494655 0.853958L4.25512 4.61442C4.32048 4.67978 4.40304 4.71279 4.4928 4.71279C4.57494 4.71279 4.66468 4.68022 4.73048 4.61442L8.49043 0.854471C8.62923 0.723252 8.6291 0.50977 8.49792 0.378596C8.36677 0.247442 8.15372 0.247442 8.02256 0.378596Z" stroke="#677387" stroke-opacity="0.4" stroke-width="0.1"/></svg></span>
					</div>
					<div class="el-api-token-remove">{{$t('remove')}}</div>
				</div>
			</div>
			<div class="el-api-tokens-add">
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
					<ul class="el-api-code-content">
						<li
							class="el-api-code-content__row"
							v-for="responseStr of doc.response"
						>
							{{responseStr}}
						</li>
					</ul>
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
					{ value: '4935eefddd20e1c2da613b3e64b65651', date: '12 January 2020 12:30', group: 'Administrators' },
					{ value: 'b7580864176a7061daab7cd82ed2aee7', date: '12 January 2020 12:30', group: 'Administrators' },
				],
				docs: [
					{
						type:     'Get',
						code:     ['curl https://api.stripe.com/v1/charges \\','-u sk_test_4eC39HqLyjWDarjtT1zdp7dc:','# The colon prevents curl from asking for a password.'],
						response: ['curl https://api.stripe.com/v1/charges \\','-u sk_test_4eC39HqLyjWDarjtT1zdp7dc:','# The colon prevents curl from asking for a password.']
					},
					{
						type:     'Insert',
						code:     ['curl https://api.stripe.com/v1/charges \\','-u sk_test_4eC39HqLyjWDarjtT1zdp7dc:','# The colon prevents curl from asking for a password.'],
						response: ['curl https://api.stripe.com/v1/charges \\','-u sk_test_4eC39HqLyjWDarjtT1zdp7dc:','# The colon prevents curl from asking for a password.']
					},
					{
						type:     'Delete',
						code:     ['curl https://api.stripe.com/v1/charges \\','-u sk_test_4eC39HqLyjWDarjtT1zdp7dc:','# The colon prevents curl from asking for a password.'],
						response: ['curl https://api.stripe.com/v1/charges \\','-u sk_test_4eC39HqLyjWDarjtT1zdp7dc:','# The colon prevents curl from asking for a password.']
					},
					{
						type:     'Update',
						code:     ['curl https://api.stripe.com/v1/charges \\','-u sk_test_4eC39HqLyjWDarjtT1zdp7dc:','# The colon prevents curl from asking for a password.'],
						response: ['curl https://api.stripe.com/v1/charges \\','-u sk_test_4eC39HqLyjWDarjtT1zdp7dc:','# The colon prevents curl from asking for a password.']
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

			},
		},
		methods:
		{
			selectTable(table)
			{
				this.selectedTable = table;
			}
		},
		mounted()
		{
			this.selectTable(this.tables[0]);
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