<template>
	<div class="export-popup-wr">
		<div class="export-popup">
			<a
				class="export-popup-btn"
				target="blank"
				:href="'/element/api/tview/exportCsv?tviewId=' + tview.id"
			>
				Export to CSV
			</a>
		</div>
	</div>
</template>
<script>
	import qs from 'qs';
	import axios from 'axios';
	export default
	{
		props: ['tview'],
	}
</script>
<style lang="scss">
	.export-popup-wr
	{
		position: absolute;
		top: calc(100% + 5px);
		right: 0;
		box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
		border: 1px solid rgba(103, 115, 135, 0.1);
		z-index: 2;
		border-radius: 2px;
		background-color: #fff;
		width:auto;
		min-width: 120px;
		transform: translate(calc(50% - 11px));
	}
	.export-popup-btn
	{
		border-radius: 2px;
		padding-top: 8px;
		padding-bottom: 8px;
		padding-left: 11px;
		font-size: 10px;
		color: rgba(25, 28, 33, 0.7);
		display: block;
		text-decoration: none;
		&:hover
		{
			background-color: rgba(103, 115, 135, 0.1);
		}
	}
</style>