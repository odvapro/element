<template>
	<div class="em-text" :class="{'em-text__table':(view != 'detail')}">
		<div class="em-text__txt" @click="openEditor()">
			{{ previewText }}
			<span class="el-empty" v-if="!previewText">{{ $t('empty') }}</span>
		</div>
		<Popup class="em-text__popup" :visible.sync="showEditor">
			<div id="el-editor"></div>
		</Popup>
	</div>
</template>
<script>
	import EditorJS from '@editorjs/editorjs';
	export default
	{
		props: ['fieldValue','fieldSettings','mode', 'view'],
		components:{},
		data()
		{
			return {
				localValue:this.fieldValue,
				showEditor:false,
				editor:false,
			}
		},
		computed:
		{
			previewText()
			{
				if(!this.localValue)
					return '';
				if(typeof this.localValue == 'object')
				{
					let blocksCount = (typeof this.localValue.blocks != 'undefined')?this.localValue.blocks.length:0;
					let time =(typeof this.localValue.time != 'undefined')?this.localValue.time:0;
					return `${blocksCount} blocks, last edit ${time}`;
				}

			}
		},
		watch:{
			showEditor(show)
			{
				if(show == false)
					this.editor.save().then((outputData) => {
						this.localValue = outputData;
						this.$emit('onChange', {
							value     : this.localValue,
							settings  : this.fieldSettings
						});
					})
			}
		},
		methods:
		{
			openEditor()
			{
				this.showEditor = true;

				let data = {};
				if(typeof this.localValue == 'string')
					data = {blocks: [{"type": "paragraph", "data": {"text": this.localValue } } ] };
				if(typeof this.localValue == 'object')
					data = this.localValue;
				this.editor = new EditorJS({
					holderId : 'el-editor',
					data     : data
				});
			}
		}
	}
</script>
<style lang="scss">
	.em-text
	{
		min-width:500px;
		&.em-text__table
		{
			min-width:auto;
			position: absolute;
			width: 100%;
			left: 0px;
			top: 0px;
			height: 100%;
			padding-left: 10px;
			padding-right: 10px;
			margin-top: 0;
		}
	}
	.em-text__txt
	{
		line-height: 17px;
		font-size: 12px;
		color: #677387;
		padding:11px 20px 11px 0;
		cursor: pointer;
	}
	.em-text__popup .popup-block
	{
		width:800px;
		height:calc(100% - 144px);
	}
</style>