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
					<li @click="showPopup($event.target, 'Properties', 'center-bottom')">Properties</li>
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
		<div class="table-vertical-scroll">
			<div class="table__min-width">
				<div class="table-row no-hover">
					<div class="table-overlay-row">
						<Checkbox/>
						<img class="table-overlay-row-option-icon" src="/images/points.svg" alt="">
					</div>
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
					<div class="table-item">
						<div class="table__add-column-item">
							<div class="table__add-col-img">
								<img src="/images/plus-white.svg" alt="">
							</div>
							<span class="table__add-col-label">Add field</span>
						</div>
					</div>
				</div>
				<div class="table-row">
					<div class="table-overlay-row">
						<Checkbox/>
						<img class="table-overlay-row-option-icon" src="/images/points.svg" alt="">
					</div>
					<div class="table-item">
						<div class="table-item-name-wrapper">
							<div class="table-item-overide-name">1sd sa dsa </div>
						</div>
					</div>
					<div class="table-item">
						<div class="table-item-name-wrapper">
							<div class="table-item-overide-name">Product name</div>
						</div>
					</div>
					<div class="table-item">
						<div class="table-item-image-col">
							<div class="table-item-img-wrapper" v-for="item in 10" @click="showPopup($event.target.closest('.table-item'), 'Upload', 'left-top')">
								<img src="/images/image.png" alt="">
							</div>
						</div>
					</div>
					<div class="table-item">
						<div class="table-item-name-wrapper">
							<div class="table-item-overide-name">Price</div>
						</div>
					</div>
					<div class="table-item">
						<TagItem name="shirt"/>
						<TagItem name="shirt"/>
					</div>
					<div class="table-item">
						<div class="table-item-checkbox">
							<Checkbox/>
						</div>
					</div>
					<div class="table-item">
						<div class="table-empty-col">

						</div>
					</div>
				</div>
				<div class="table-row">
					<div class="table-overlay-row">
						<Checkbox/>
						<img class="table-overlay-row-option-icon" src="/images/points.svg" alt="">
					</div>
					<div class="table-item">
						<div class="table-item-name-wrapper">
							<div class="table-item-overide-name">1</div>
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
						</div>
					</div>
					<div class="table-item">
						<div class="table-item-name-wrapper">
							<div class="table-item-overide-name">Price</div>
						</div>
					</div>
					<div class="table-item">
						<TagItem name="shirt"/>
					</div>
					<div class="table-item">
						<div class="table-item-checkbox">
							<Checkbox/>
						</div>
					</div>
					<div class="table-item">
						<div class="table-empty-col">

						</div>
					</div>
				</div>
				<div class="table-row">
					<div class="table-overlay-row">
						<Checkbox/>
						<img class="table-overlay-row-option-icon" src="/images/points.svg" alt="">
					</div>
					<div class="table-item">
						<div class="table-item-name-wrapper">
							<div class="table-item-overide-name">1</div>
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
						</div>
					</div>
					<div class="table-item">
						<div class="table-item-name-wrapper">
							<div class="table-item-overide-name">Price</div>
						</div>
					</div>
					<div class="table-item">
						<TagItem name="Pans"/>
					</div>
					<div class="table-item">
						<div class="table-item-checkbox">
							<Checkbox/>
						</div>
					</div>
					<div class="table-item">
						<div class="table-empty-col">

						</div>
					</div>
				</div>
			</div>
		</div>
		<Pagination/>
	</div>
</template>
<script>
	import Checkbox from '@/components/forms/Checkbox.vue';
	import Pagination from '@/components/layouts/Pagination.vue';
	import TagItem from '@/components/forms/TagItem.vue';
	import Popup from '@/mixins/popup.js';
	export default
	{
		mixins: [Popup],
		components: {Checkbox, TagItem, Pagination},
		data()
		{
			return {
				points:
				{
					isDrug: false,
					posX: 0
				},
				openProperties: false
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

				if (this.$cookie.get('drugPosition') > 200)
					app.style.gridTemplateColumns = this.$cookie.get('drugPosition') + 'px auto';
				else
					app.style.gridTemplateColumns = '400px auto';

				document.addEventListener('mousedown', function(event)
				{
					if (event.target.classList.value != 'drug')
						return false;

					self.points.isDrug = true;
				}, false);

				document.addEventListener('mousemove', function(event)
				{
					if (!self.points.isDrug)
						return false;

					if (event.pageX < 200 || event.pageX > 480)
						return false;

					self.points.posX = event.pageX;
					app.style.gridTemplateColumns = event.pageX + 'px auto'
				}, false);
				document.addEventListener('mouseup', function(event)
				{
					self.points.isDrug = false;
					self.$cookie.set('drugPosition', self.points.posX, 111);
				}, false);
			},
		},
		created()
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
		padding-right: 95px;
	}
	.table-wrapper
	{
		padding: 23px 0 23px 21px;
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
			cursor: pointer;
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
	.table-overlay-row-option-icon
	{
		width: 19px;
		height: 2px;
		position: relative;
		z-index: 2;
	}
	.table-item
	{
		display: flex;
		word-break: break-word;
		align-items: center;
		height: 49px;
		padding-right: 10px;
		min-width: 140px;
		width: 140px;
		overflow: hidden;
		padding-left: 9px;
		position: relative;
		border-right: 1px solid rgba(103, 115, 135, 0.1);
		&:last-child
		{
			border-right: none;
		}
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
		.table-overlay-row
		{
			display: none;
		}
		display: flex;
		border-top: 1px solid rgba(103, 115, 135, 0.1);
		position: relative;
		&.no-hover:hover .table-overlay-row
		{
			display: none;
		}
		&:hover
		{
			.table-overlay-row
			{
				background-color: rgba(103, 115, 135, 0.1);
				width: 100%;
				padding-left: 10px;
				display: flex;
				align-items: center;
				position: absolute;
				height: 100%;
				top: 0;
				left: 0;
				&:after
				{
					content: '';
					background-color: #f0f1f3;
					width: 139px;
					height: 100%;
					z-index: 1;
					position: absolute;
					left: 0;
				}
			}
		}
		.checkbox-label
		{
			margin-right: 8px;
			z-index: 2;
			span
			{
				background-color: #fff;
			}
		}
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
		cursor: pointer;
		margin-bottom: 3px;
		img
		{
			width: 100%;
			height: 100%;
			object-fit: cover;
		}
	}
	.table-item-checkbox
	{
		width: 100%;
		height: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.table-vertical-scroll
	{
		overflow: auto;
	}
	.table__min-width
	{
		min-width: 900px;
	}
	.table__add-column-item
	{
		display: flex;
		align-items: center;
		cursor: pointer;
	}
	.table__add-col-label
	{
		color: rgba(103, 115, 135, 0.4);
		font-size: 12px;
	}
	.table__add-col-img
	{
		margin-right: 9px;
		img
		{
			width: 100%;
			height: 100%;
			object-fit: contain;
		}
	}
</style>