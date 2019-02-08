<template>
	<div class="input-file-wrapper">
		<input type="file" name="file" id="file" class="input-file" />
		<label for="file">Choose File</label>
	</div>
</template>
<style>
	.input-file
	{
		width: 0.1px;
		height: 0.1px;
		opacity: 0;
		overflow: hidden;
		position: absolute;
		z-index: -1;
	}
	.input-file + label
	{
		font-size: 12px;
		padding: 7px 11px;
		line-height: 14px;
		color: white;
		background: rgba(25, 28, 33, 0.7);
		border-radius: 2px;
		display: inline-block;
		cursor: pointer;
	}

	.input-file:focus + label,
	.input-file + label:hover
	{
		background: rgba(25, 28, 33, 0.5);
	}
</style>