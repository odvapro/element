<template>
	<div class="table-wrapper">
		<div class="table-head">
			<div class="table-head-name">
				<div class="table-icon-wrapper">
					<img src="/images/tableicon.svg" alt="">
				</div>
				<div class="table-name-wrapper">
					<div class="table-real-name">Products</div>
					<div class="table-overide-name">Products</div>
				</div>
			</div>
			<div class="table-head-options">
				<ul class="table-head-options-list">
					<li>Views</li>
					<li>Properties</li>
					<li>Sort</li>
					<li>Filter</li>
					<li>...</li>
				</ul>
				<div class="table-head-add-btn">
					<div class="table-head-btn-wrapper">
						<img src="/images/plus.svg" alt="">
					</div>
					<span class="table-head-add-btn-name">
						Add Element
					</span>
				</div>
			</div>
		</div>
		<div class="table-row">
			<div class="table-item">
				<div class="table-item-img">
					<img src="/images/sharp.svg" alt="">
				</div>
				<div class="table-item-name-wrapper">
					<div class="table-item-overide-name">Identifier</div>
					<div class="table-item-real-name">id</div>
				</div>
			</div>
			<div class="table-item">
				<div class="table-item-img">
					<img src="/images/sharp.svg" alt="">
				</div>
				<div class="table-item-name-wrapper">
					<div class="table-item-overide-name">Product name</div>
					<div class="table-item-real-name">name</div>
				</div>
			</div>
			<div class="table-item">
				<div class="table-item-img">
					<img src="/images/sharp.svg" alt="">
				</div>
				<div class="table-item-name-wrapper">
					<div class="table-item-overide-name">Picture</div>
					<div class="table-item-real-name">pic</div>
				</div>
			</div>
			<div class="table-item">
				<div class="table-item-img">
					<img src="/images/sharp.svg" alt="">
				</div>
				<div class="table-item-name-wrapper">
					<div class="table-item-overide-name">Price</div>
					<div class="table-item-real-name">Price</div>
				</div>
			</div>
			<div class="table-item">
				<div class="table-item-img">
					<img src="/images/sharp.svg" alt="">
				</div>
				<div class="table-item-name-wrapper">
					<div class="table-item-overide-name">Categories</div>
					<div class="table-item-real-name">sections</div>
				</div>
			</div>
			<div class="table-item">
				<div class="table-item-img">
					<img src="/images/sharp.svg" alt="">
				</div>
				<div class="table-item-name-wrapper">
					<div class="table-item-overide-name">Active</div>
					<div class="table-item-real-name">act</div>
				</div>
			</div>
		</div>
		<div class="table-row">
			<div class="table-item">
				<div class="table-item-name-wrapper">
					<div class="table-item-overide-name">asdddslfkjdslkfjdsklafj</div>
				</div>
			</div>
			<div class="table-item">
				<div class="table-item-name-wrapper">
					<div class="table-item-overide-name">Product name</div>
				</div>
			</div>
			<div class="table-item">
				<div class="table-item-image-col">
					<a href="#" class="table-item-img-wrapper">
						<img src="/images/image.png" alt="">
					</a>
					<a href="#" class="table-item-img-wrapper">
						<img src="/images/image.png" alt="">
					</a>
					<a href="#" class="table-item-img-wrapper">
						<img src="/images/image.png" alt="">
					</a>
					<a href="#" class="table-item-img-wrapper">
						<img src="/images/image.png" alt="">
					</a>
					<a href="#" class="table-item-img-wrapper">
						<img src="/images/image.png" alt="">
					</a>
					<a href="#" class="table-item-img-wrapper">
						<img src="/images/image.png" alt="">
					</a>
					<a href="#" class="table-item-img-wrapper">
						<img src="/images/image.png" alt="">
					</a>
					<a href="#" class="table-item-img-wrapper">
						<img src="/images/image.png" alt="">
					</a>
					<a href="#" class="table-item-img-wrapper">
						<img src="/images/image.png" alt="">
					</a>
				</div>
			</div>
			<div class="table-item">
				<div class="table-item-name-wrapper">
					<div class="table-item-overide-name">Price</div>
				</div>
			</div>
			<div class="table-item">
				<div class="table-bound-item">
					shirt
				</div>
				<div class="table-bound-item">
					Pans
				</div>
			</div>
			<div class="table-item">
				<div class="table-item-checkbox">
					<label class="checkbox-label">
						<input type="checkbox" class="checkbox">
						<span>
							<img src="/images/check.svg" alt="">
						</span>
					</label>
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
				points:
				{
					isDrug: false,
					x: 0,
					y: 0
				}
			}
		},
		methods:
		{
			/**
			 * Инициализация событий для уменьшения/увеливения сайдбара
			 */
			initEventScale()
			{
				var app = document.getElementsByClassName('app-wrapper')[0],
					self = this;
				document.addEventListener('mousedown', function(event)
				{
					event.preventDefault();
					event.stopPropagation();
					self.points.isDrug = true;
					self.points.x = event.pageX;
					self.points.y = event.pageY;
				}, false);

				document.addEventListener('mousemove', function(event)
				{
					event.preventDefault();
					event.stopPropagation();

					if (!self.points.isDrug)
						return false;
					if (event.pageX < 200 || event.pageX > 480)
						return false;

					app.style.gridTemplateColumns = event.pageX + 'px auto'
				}, false);
			},
		},
		mounted()
		{
			this.initEventScale();
		}
	}
</script>
<style lang="scss">
	.table-head
	{
		display: flex;
		justify-content: space-between;
		align-items: flex-end;
		margin-bottom: 16px;
	}
	.table-wrapper
	{
		padding: 23px 95px 23px 21px;
	}
	.table-head-name
	{
		display: flex;
		align-items: center;
	}
	.table-real-name
	{
		font-family: $rBold;
		font-size: 20px;
		color: #191C21;
		line-height: 22px;
		text-transform: capitalize;
	}
	.table-overide-name
	{
		color: rgba(103, 115, 135, 0.4);
		font-size: 10px;
		text-transform: lowercase;
	}
	.table-icon-wrapper
	{
		margin-right: 12px;
	}
	.table-head-options
	{
		display: flex;
		align-items: center;
	}
	.table-head-options-list
	{
		padding-right: 6px;
		display: flex;
		li
		{
			color: rgba(25, 28, 33, 0.7);
			font-size: 12px;
			margin-right: 21px;
		}
	}
	.table-head-add-btn
	{
		display: flex;
		background-color: rgba(25, 28, 33, 0.7);
		border-radius: 2px;
		padding: 0 11px 0 32px;
		position: relative;
		font-size: 12px;
		color: #fff;
		height: 30px;
		align-items: center;
		color: #fff;
	}
	.table-head-btn-wrapper
	{
		position: absolute;
		left: 10px;
		width: 12px;
		height: 12px;
		top: calc(50% - 6px);
	}
	.table-head-add-btn-name
	{
		color: #fff;
	}
	.table-item-img
	{
		width: 14px;
		height: 14px;
		margin-right: 6px;
	}
	.table-item
	{
		display: flex;
		word-break: break-word;
		align-items: center;
		height: 49px;
		padding-right: 10px;
		min-width: 103px;
		width: 120px;
		overflow: hidden;
		padding-left: 9px;
		border-right: 1px solid rgba(103, 115, 135, 0.1);
	}
	.table-item-overide-name
	{
		line-height: 12px;
		font-size: 12px;
		color: #677387;
		text-transform: capitalize;
	}
	.table-item-real-name
	{
		line-height: 12px;
		font-size: 10px;
		color: rgba(103, 115, 135, 0.4);
		text-transform: lowercase;
	}
	.table-row
	{
		display: flex;
		border-top: 1px solid rgba(103, 115, 135, 0.1);
		border-bottom: 1px solid rgba(103, 115, 135, 0.1);
	}
	.table-item-image-col
	{
		display: flex;
		flex-wrap: wrap;
	}
	.table-item-img-wrapper
	{
		width: 14px;
		height: 14px;
		margin-right: 3px;
		margin-bottom: 3px;
		img
		{
			width: 100%;
			height: 100%;
			object-fit: cover;
		}
	}
	.table-bound-item
	{
		padding: 4px 8px;
		background-color: rgba(124, 119, 145, 0.1);
		border-radius: 2px;
		font-size: 10px;
		margin-right: 2px;
		color: #7C7791;
	}
	//checkbox
	.checkbox-label
	{
		display: inline-block;
		position: relative;
		padding-left: 15px;
		font-size: 14px;
		height: 15px;
		color: #334D66;
		cursor: pointer;
	}
	.checkbox
	{
		visibility: hidden;
		position: absolute;
	}
	.checkbox:not(checked) + span
	{
		display: flex;
		align-items: center;
		justify-content: center;
		width: 11px;
		height: 11px;
		border: 1px solid rgba(103, 115, 135, 0.4);
		border-radius: 2px;
		position: absolute;
		left: 0;
		transition: border 0.3s;
	}
	.checkbox:checked + span
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

	.checkbox:checked:hover + span
	{
		transition: background 0.3s;
		border: 1px solid rgba(103, 115, 135, 0.5);
	}
	.checkbox:not(checked):hover + span
	{
		border: 1px solid rgba(103, 115, 135, 0.8);
		transition: border 0.3s;
	}
	//---
	.table-item-checkbox
	{
		width: 100%;
		height: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
	}
</style>