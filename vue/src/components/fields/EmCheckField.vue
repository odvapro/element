<template>
	<div class="em-check-wrapper">
		<label class="em-check-label">
			<input type="checkbox" v-model="fieldValue.value" class="em-check">
			<span>
				<img src="/images/check.svg" alt="">
			</span>
		</label>
	</div>
</template>
<script>
	export default
	{
		props: ['fieldValue']
	}
</script>
<style lang="scss">
.em-check-wrapper
{
	display: inherit;
	.em-check-label
	{
		display: inline-block;
		position: relative;
		padding-left: 13px;
		font-size: 14px;
		height: 12px;
		color: #334D66;
		cursor: pointer;
	}
	.em-check
	{
		visibility: hidden;
		position: absolute;
	}
	.em-check:not(checked) + span
	{
		display: flex;
		align-items: center;
		justify-content: center;
		width: 13px;
		height: 13px;
		border: 1px solid rgba(103, 115, 135, 0.4);
		border-radius: 2px;
		position: absolute;
		left: 0;
		transition: border 0.3s;
		background-color: #fff;
	}
	.em-check:checked + span
	{
		background: #7C7791;
		border: 1px solid #7C7791;
		background-repeat: no-repeat;
		background-size: contain;
		transition: background 0.3s;
		img
		{
			width: 7px;
			height: 7px;
			object-fit: contain;
		}
	}

	.em-check:checked:hover + span
	{
		transition: background 0.3s;
		border: 1px solid rgba(103, 115, 135, 0.5);
	}
	.em-check:not(checked):hover + span
	{
		border: 1px solid rgba(103, 115, 135, 0.8);
		transition: border 0.3s;
	}
}
</style>