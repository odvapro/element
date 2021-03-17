<template>
	<div class="select" data-test="filter-column">
		<button
			class="select__trigger"
			@click="toggleDropdown"
			v-click-outside="closeDropdown"
			:class="{
				active: active,
				disabled: disabled
			}"
		>
			<div class="select__content" v-html="content"></div>
			<template v-if=!disabled>
				<span class="select__arrow">
					<svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M9.26399 0.171389L9.26396 0.171427L5.00466 4.43907L0.736982 0.171389C0.580928 0.0153346 0.327417 0.0153346 0.171362 0.171389C0.0153076 0.327444 0.0153076 0.580955 0.171362 0.737009L4.71346 5.27911C4.79126 5.3569 4.88943 5.39615 4.99628 5.39615C5.09399 5.39615 5.20081 5.35738 5.27909 5.27911L9.82063 0.737571C9.98584 0.581446 9.98569 0.327466 9.82962 0.171389C9.67356 0.0153346 9.42005 0.0153346 9.26399 0.171389Z" fill="#677387" stroke="#677387" stroke-width="0.1"/>
					</svg>
				</span>
				<span class="select__arrow select__arrow--simple">
					<svg width="9" height="5" viewBox="0 0 9 5" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.46257 0.413952C8.35094 0.302324 8.16954 0.302324 8.05792 0.413952L4.49978 3.97907L0.934661 0.413952C0.823033 0.302324 0.641638 0.302324 0.53001 0.413952C0.418382 0.525579 0.418382 0.706975 0.53001 0.818603L4.29047 4.57907C4.34629 4.63488 4.41606 4.66279 4.4928 4.66279C4.56257 4.66279 4.63931 4.63488 4.69513 4.57907L8.45559 0.818603C8.57419 0.706975 8.5742 0.525579 8.46257 0.413952Z" fill="#677387" fill-opacity="0.4"/><path d="M8.02256 0.378596L8.02253 0.378631L4.49974 3.90832L0.970016 0.378596C0.838862 0.247442 0.625809 0.247442 0.494655 0.378596C0.363501 0.50975 0.363501 0.722804 0.494655 0.853958L4.25512 4.61442C4.32048 4.67978 4.40304 4.71279 4.4928 4.71279C4.57494 4.71279 4.66468 4.68022 4.73048 4.61442L8.49043 0.854471C8.62923 0.723252 8.6291 0.50977 8.49792 0.378596C8.36677 0.247442 8.15372 0.247442 8.02256 0.378596Z" stroke="#677387" stroke-opacity="0.4" stroke-width="0.1"/></svg>
				</span>
			</template>
		</button>
		<transition name="fade">
			<ul class="select__dropdown" v-if="active">
				<slot></slot>
			</ul>
		</transition>
	</div>
</template>
<script>
	export default
	{
		props:
		{
			defaultText :String,
			disabled    :
			{
				tupe    : Boolean,
				default : false
			}
		},
		data()
		{
			return {
				active  : false,
				value   : '',
				content : false
			}
		},

		mounted()
		{
			this.content = this.defaultText;
		},
		methods:
		{
			/**
			 * Open/close select dropdown
			 */
			toggleDropdown: function()
			{
				if (!this.disabled)
				this.active = !this.active
			},
			/**
			 * Close select dropdown
			 */
			closeDropdown: function()
			{
				this.active = false;
			},
			/**
			 * Set option html content to select button
			 */
			setContent: function(newValue)
			{
				this.content = newValue;
			}
		},
		watch:
		{
			/**
			 * watch change defaultText variable
			 */
			'defaultText': function(newValue)
			{
				this.setContent(newValue);
			}
		}
	}
</script>
<style lang="scss">
	.select
	{
		position: relative;
		font-style: normal;
		font-weight: normal;
		font-size: 13px;
		color: #677387;
		&--simple
		{
			.select__trigger
			{
				border-color: transparent;
				&:hover,&.active{border-color: transparent;}
				&.active .select__content{color: #677387;}
			}
			.select__content{color: rgba(103, 115, 135, 0.4);}
			.select__arrow{display: none;}
			.select__arrow--simple{display: block;}
		}
	}
	.select__content
	{
		flex-basis: 90%;
		font-weight:400;
		font-size:12px;
		line-height:14px;
		letter-spacing:0em;
		color:rgba(103,115,135,1);
		white-space: nowrap;
	}
	.select__trigger
	{
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 0 17px;
		padding-left:10px;
		padding-right:10px;
		text-align: left;
		color: #677387;
		transition: .2s;
		cursor: pointer;
		height: 30px;
		border:1px solid rgba(103,115,135,0.4);
		border-radius:2px;
		cursor: pointer;
		background: #fff;
	}
	.select__trigger:hover
	{
		border:1px solid rgba(103,115,135,0.7);;
		transition: .2s;
	}
	.select__trigger.active
	{
		border-color: rgba(103,115,135,1);
		transition: .2s;
	}
	.select__trigger.active .select__arrow svg path {fill: #191C21; }
	.select__trigger:focus {outline: none }
	.select__arrow
	{
		margin-left:5px;
		&--simple{display: none;}
	}
	.select__dropdown
	{
		border-top: 0;
		position: absolute;
		min-width: 150px;
		top: calc(100% + 2px);
		right: 0;
		left: 0;
		z-index: 999;
		margin: 0;
		list-style: none;
		border:1px solid rgba(103,115,135,0.1);
		background-color: rgba(255,255,255,1);
		border-radius:2px;
		box-shadow: 0px 4px 6px rgba(200,200,200,0.25);
	}
	.select__dropdown li
	{
		padding-left:12.406799316406px;
		height: 40px;
		line-height: 40px;
		cursor: pointer;
	}
	.select__dropdown li:hover, .select__dropdown li.active
	{
		color:rgba(25,28,33,0.7);
		background-color: rgba(103,115,135,0.1);
	}
	.select .fade-enter-active, .select .fade-leave-active {transition: all .2s; }
	.select .fade-enter, .select .fade-leave-to {opacity: 0; top: 90%; }
</style>
