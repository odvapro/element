<template>
	<div class="pagination-wrapper">
		<ul>
			<li v-for="item in getPaginatorArr" :class="{active: current == item, points: item == '...'}" @click="setPage(item)">{{(item != '...') ? item : ''}}</li>
		</ul>
		<div class="pagination__text">
			{{$t('pagination.elements_per_page')}}<span contenteditable="true" @input="setLimit">{{currentLimit}}</span>*
		</div>
	</div>
</template>
<script>
	export default
	{
		props: ['current', 'maxPage', 'currentLimit'],
		data()
		{
			return {
				range : 2,
				limit : (this.currentLimit) ? this.currentLimit : 20,
				page  : 1
			}
		},
		computed:
		{
			/**
			массив страниц
			*/
		 	getPaginatorArr()
			{
				var arr    = [1],
				maxRange   = this.range * 2 + 1,
				addedItems = 0,
				start      = 1;

				if(this.current - this.range <= 1)
					start = 1;
				else if(this.current + this.range >= this.maxPage)
					start = this.maxPage - maxRange;
				else
					start = this.current - this.range;

				for(var i = start; i < this.maxPage; i++)
				{
					if(i <= 1)
						continue;

					if(i >= this.maxPage)
						continue;

					if(arr.indexOf(i) !== -1)
						continue;

					arr.push(i);
					addedItems++;

					if(addedItems >= maxRange)
						break;
				}

				arr.push(this.maxPage);

				var result = [],
				prevItem = 1;

				for(var index in arr)
				{
					var item = arr[index];

					if(item - prevItem > 1)
						result.push('...');

					result.push(item);
					prevItem = item;
				}

				return result;
			}
		},
		methods:
		{
			setPage(page)
			{
				if(page == '...' || page == this.current)
					return false;

				this.page = page;
				this.$emit('change', {page:page,limit:this.limit});
			},
			setLimit(event)
			{
				this.page  = 1;

				if(+event.target.innerText <= 0)
					return false;

				this.limit = +event.target.innerText;
				this.$emit('change', {page :1,limit:this.limit});
			}
		},
	}
</script>
<style lang="scss">
	.pagination-wrapper
	{
		padding: 14px 0;
		display: flex;
		align-items: center;
		ul
		{
			display: flex;
			align-items: center;
		}
		li
		{
			font-size: 12px;
			color: rgba(25, 28, 33, 0.7);
			width: 21px;
			height: 25px;
			cursor: pointer;
			display: flex;
			align-items: center;
			justify-content: center;
			border-radius: 2px;
			margin-right: 2px;
			&.active
			{
				background-color: rgba(103, 115, 135, 0.1);
				color: #191C21;
			}
			&.points
			{
				position: relative;
				padding: 0 7px;
				width: 35px;
				pointer-events: none;
				&:after
				{
					content: '...';
					position: absolute;
					top: 3px;
				}
			}
		}
	}
	.pagination__text
	{
		font-style: normal;
		font-weight: 500;
		font-size: 12px;
		color: rgba(25, 28, 33, 0.7);
		margin-left: 16px;
	}
</style>