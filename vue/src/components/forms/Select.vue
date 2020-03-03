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
			<span class="select__arrow">
				<svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M9.26399 0.171389L9.26396 0.171427L5.00466 4.43907L0.736982 0.171389C0.580928 0.0153346 0.327417 0.0153346 0.171362 0.171389C0.0153076 0.327444 0.0153076 0.580955 0.171362 0.737009L4.71346 5.27911C4.79126 5.3569 4.88943 5.39615 4.99628 5.39615C5.09399 5.39615 5.20081 5.35738 5.27909 5.27911L9.82063 0.737571C9.98584 0.581446 9.98569 0.327466 9.82962 0.171389C9.67356 0.0153346 9.42005 0.0153346 9.26399 0.171389Z" fill="#677387" stroke="#677387" stroke-width="0.1"/>
				</svg>
			</span>
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
<style>
	.select
	{
		position: relative;
		font-style: normal;
		font-weight: normal;
		font-size: 13px;
		color: #677387;
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
	.select__arrow{margin-left:5px;}
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