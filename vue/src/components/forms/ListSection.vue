<template>
	<div class="list-section">
		<div class="list-section__left">
			<button
				class="list-section__fold"
				:class="{'list-section__fold--open':open}"
				@click.stop="fold"
			>
				<svg width="11" height="12" viewBox="0 0 11 12" fill="none" xmlns="http://www.w3.org/2000/svg">
					<g clip-path="url(#clip0_2631_4952)">
						<path d="M2.92048 1.6811L2.92051 1.68113L7.24789 6.00005L2.92048 10.3275C2.76752 10.4804 2.76752 10.7289 2.92048 10.8819C3.07343 11.0348 3.32193 11.0348 3.47488 10.8819L8.07101 6.28575C8.1473 6.20945 8.18572 6.11336 8.18572 6.00855C8.18572 5.91281 8.14768 5.80802 8.07101 5.73135L3.47533 1.13567C3.32232 0.973609 3.07346 0.973718 2.92048 1.12669C2.76752 1.27965 2.76752 1.52814 2.92048 1.6811Z" fill="#677387" stroke="#677387" stroke-width="0.0846154"/>
					</g>
					<defs>
						<clipPath id="clip0_2631_4952">
							<rect width="11" height="11" fill="white" transform="translate(0 11.5) rotate(-90)"/>
						</clipPath>
					</defs>
				</svg>
			</button>
			<span>
				<slot></slot>
			</span>
		</div>
		<div class="list-section__right">
			<button v-if="!selected" class="list-section__select" @click.stop="select"> выбрать </button>
			<button v-if="selected" class="list-section__remove" @click.stop="remove">
				<svg width="9" height="9">
					<use xlink:href="#plus-white"></use>
				</svg>
			</button>
		</div>
	</div>
</template>
<script>
	export default
	{
		props:
		{
			selected: {
				type: Boolean,
				default: false,
			},
		},
		data()
		{
			return {
				open:false
			}
		},
		methods:
		{
			/**
			 * Choose option and close dropdown
			 */
			select:function(event)
			{
				if (this.selected)
					return;
				this.$emit('select');
				setTimeout(() => { this.$parent.selectEvent(); }, 100);
			},
			/**
			 * Remove item
			 */
			remove()
			{
				this.$emit('remove');
				setTimeout(() => { this.$parent.removeEvent(); }, 100);
			},
			/**
			 * Fold/unfold section
			 */
			fold(section)
			{
				this.open = !this.open;
			}
		}
	}
</script>
<style lang="scss">
	.list-section
	{
		position: relative;
		display: flex;
		align-items:center;
		justify-content: space-between;
		min-height: 28px;
		padding: 0 3px;
		&:hover{
			background: rgba(124, 119, 145, 0.1);
		}
		span
		{
			height:20px;
			line-height: 20px;
			padding:0 8px 0 3px;
			font-size: 12px;
			margin-right: 4px;
			color: rgba(25, 28, 33, 0.7);
			white-space: nowrap;
			display:inline-flex;
			position: relative;
			align-items:center;
			svg{
				margin-right: 5px;
			}
		}
	}
	.list-section__fold
	{
		border:0px;
		background: none;
		cursor: pointer;
		padding:2px 5px;
		border-radius: 3px;
		&:hover{background: rgba(124, 119, 145, 0.1); }
		&--open svg{transform: rotate(90deg);}
	}
	.list-section__remove
	{
		border:0px;
		background: none;
		cursor: pointer;
		height: 22px;
		width: 22px;
		border-radius: 3px;
		padding: 0px;
		display: flex;
		align-items: center;
		justify-content: center;
		&:hover{
			background: rgba(124, 119, 145, 0.1);
		}
		svg { stroke:#677387; transform: rotate(45deg); }
	}
	.list-section__select
	{
		color:#677387;
		font-size: 10px;
		padding: 0px;
		border:0px;
		font-weight: normal;
		background: none;
		cursor: pointer;
	}
	.list-section__left
	{
		display: flex;
		align-items:center;	
	}
	.list-section__right
	{
		display: flex;
		align-items:center;
		padding-right: 3px;
	}
</style>
