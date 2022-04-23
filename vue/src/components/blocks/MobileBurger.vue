<template>
	<div
		class="el-burder"
		@click="updateSidebarState"
	>
		<span></span>
		<span></span>
		<span></span>
	</div>
</template>

<script>
import { mapGetters } from 'vuex';

export default
{
	computed:
	{
		...mapGetters([
			'isShowSidebar',
		]),
	},
	methods:
	{
		updateSidebarState() { this.$store.commit('updateShowSidebar', !this.isShowSidebar); },
	},
};
</script>

<style lang="scss">
	.el-burder
	{
		width: fit-content;
		cursor: pointer;
		span
		{
			display: block;
			background-color: #5E6064;
			border-radius: 4px;
			height: 2px;
			width: 20px;
			margin-bottom: 4px;
			&:last-child { margin-bottom: 0; }
		}
	}
</style>
