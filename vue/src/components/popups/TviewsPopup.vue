<template>
	<div class="tviews-popup__wrapper" @click.stop>
		<div class="tviews-popup__rows">
			<div class="tviews-popup__row">
				<div class="tviews-popup__operators-wrapper">

				</div>
				<div class="tviews-popup__delete-row-icon-wrapper" @click.stop="deleteRowFilter(index)">
					<div class="tviews-popup__delete-row-icon">
						<svg width="12" height="12">
							<use xlink:href="#plus-white"></use>
						</svg>
					</div>
				</div>
			</div>
		</div>
		<button class="tviews-popup__add-view" @click="addFilterRow">Add view</button>
	</div>
</template>
<style lang="scss">
	.tviews-popup__operators-wrapper
	{
		display: flex;
		align-items: center;
	}
	.tviews-popup__delete-row-icon-wrapper
	{
		width: 24px;
		height: 24px;
		display: flex;
		justify-content: center;
		align-items: center;
		border-radius: 2px;
		&:hover
		{
			background-color: rgba(103, 115, 135, 0.1);
		}
	}
	.tviews-popup__delete-row-icon
	{
		width: 12px;
		height: 12px;
		transform: rotate(45deg);
	}
	.tviews-popup__row
	{
		margin-bottom: 15px;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	.tviews-popup__wrapper
	{
		position: absolute;
		top: calc(100% + 5px);
		right: 0;
		padding: 15px;
		min-width: 320px;
		z-index: 2;
		box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
		border: 1px solid rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		background-color: #fff;
	}
	.tviews-popup__add-view
	{
		background-color: rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		border: none;
		padding: 6px 10px;
		font-size: 12px;
		color: #677387;
		cursor: pointer;
	}
	.tviews-popup__select-item-input
	{
		width: 80px;
		border: 1px solid rgba(103, 115, 135, 0.4);
		border-radius: 2px;
		padding: 6px 10px;
		margin-right: 10px;
		color: #677387;
		&::placeholder
		{
			color: rgba(103, 115, 135, 0.4);
		}
	}
</style>