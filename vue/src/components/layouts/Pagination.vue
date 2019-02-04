<template>
	<div class="pagination-wrapper">
		<ul>
			<li v-for="item in getPaginatorArr" :class="{active: value == item, points: item == '...'}" @click="setPage(item)">{{(item != '...') ? item : ''}}</li>
		</ul>
	</div>
</template>
<script>
	export default
	{
		data()
		{
			return {
				value: 3,
				range: 2,
				maxPage: 10
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

				if(this.value - this.range <= 1)
					start = 1;
				else if(this.value + this.range >= this.maxPage)
					start = this.maxPage - maxRange;
				else
					start = this.value - this.range;

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
				if(page == '...')
					return false;

				this.value = page;
			}
		}
	}
</script>
<style lang="scss">
	.pagination-wrapper
	{
		padding: 14px 0;
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
				&:after
				{
					content: '...';
					position: absolute;
					top: 3px;
				}
			}
		}
	}
</style>