<template>
	<div class="table-groups">
		<div class="table-groups__line">
			<div class="table-groups__name">
				<span>Administrators</span>
				<em>3 members</em>
			</div>
			<div class="table-groups__select">
				<Select defaultText="Full Access">
					<SelectOption >Full Access</SelectOption>
					<SelectOption >Can read</SelectOption>
					<SelectOption >Remove</SelectOption>
				</Select>
			</div>
		</div>
		<div class="table-groups__line">
			<div class="table-groups__name">
				<span>Editors</span>
				<em>3 members</em>
			</div>
			<div class="table-groups__select">
				<Select defaultText="Full Access">
					<SelectOption >Full Access</SelectOption>
					<SelectOption >Can read</SelectOption>
					<SelectOption >Remove</SelectOption>
				</Select>
			</div>
		</div>
		<div class="table-groups__line">
			<div class="table-groups__name">
				<span>Administrators</span>
				<em>3 members</em>
			</div>
			<div class="table-groups__select">
				<Select defaultText="Full Access">
					<SelectOption >Full Access</SelectOption>
					<SelectOption >Full Access</SelectOption>
				</Select>
			</div>
		</div>
		<div class="table-groups__buttons">
			<button class="el-gbtn">Disable Access</button>
			<button class="el-btn">Add a Group</button>
		</div>
	</div>
</template>
<script>
	export default
	{
		data()
		{
			return {
			}
		},
		methods:
		{
		}
	}
</script>
<style lang="scss">
	.table-groups
	{
		position: absolute;
		background: #fff;
		top:40px;
		width:310px;
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