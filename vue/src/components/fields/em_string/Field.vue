<template>
	<div class="em-string__wrapper">
		<div class="em-string" @click="openEdit()">{{ fieldValue }}</div>
		<div
			class="em-string__edit"
			v-click-outside="closeEdit"
			v-if="showEdit"
			ref="editString"
			contenteditable
			@input="changeValue"
			@keydown.esc="closeEdit"
		></div>
	</div>
</template>
<script>
	export default
	{
		props: ['fieldValue','fieldSettings','mode', 'view'],
		data()
		{
			return {
				showEdit:false
			}
		},
		methods:
		{
			/**
			 * Send change current value
			 */
			changeValue(event)
			{
				this.$emit('onChange', {
					value     : event.target.innerText,
					settings  : this.fieldSettings
				});
			},
			/**
			 * Opens block with text editor
			 */
			openEdit()
			{
				if(this.mode != 'edit')
					return false;

				this.showEdit = true;
				this.$nextTick(function()
				{
					this.$refs.editString.innerText = this.fieldValue;
					let range = document.createRange();
					range.selectNodeContents(this.$refs.editString);
					range.collapse(false);
					let selection = window.getSelection();
					selection.removeAllRanges();
					selection.addRange(range);
				});
			},
			/**
			 * Closes block with text editor
			 */
			closeEdit()
			{
				this.showEdit = false;
			},
			onEditString(e)
			{
				this.fieldValue =  e.target.innerText;
			},
		}
	}
</script>
<style lang="scss">
	.em-string__wrapper
	{
		height: 100%;
		min-width: 100%;
		position: absolute;
		left: 0px;
	}
	.em-string
	{
		line-height: 49px;
		font-size: 12px;
		color: #677387;
		text-transform: none;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		position: absolute;
		width:100%;
		left:0px;
		top:0px;
		height: 100%;
		padding-left: 10px;
		padding-right: 10px;
		input { word-break: break-all; }
	}
	.em-string--focused
	{
		background-color: #efefef;
	}
	.detail-field-box .em-string{line-height: 41px;}

	.em-string__edit
	{
		position: absolute;
		top:-5px;
		left:-5px;
		width:calc(100% + 10px);
		min-width: 169px;
		min-height: 60px;
		background: #fff;
		padding-left:10px;
		z-index: 1;
		border-radius: 2px;
		box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
		border: 1px solid rgba(103, 115, 135, 0.1);
		padding-bottom:10px;
		padding-top:10px;

		line-height: 18px;
		font-size: 12px;
		color: #677387;
	}
</style>
