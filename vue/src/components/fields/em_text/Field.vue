<template>
	<div class="em-text" :class="{'em-text--table':(view != 'detail')}">
		<div class="em-text__txt" @click="openEditor()">
			{{ previewText }}
			<span class="el-empty" v-if="!previewText">{{ $t('empty') }}</span>
		</div>
		<Popup class="em-text__popup" :visible="showEditor" :canCloseByEsc="false">
			<div class="em-text__editor-wrapper">
				<div class="em-text__popup-head">
					<div class="em-text__label-wrapper">
						<div class="em-text__popup-name">{{fieldName}}</div>
						<div class="em-text__popup-code">{{fieldCode}}</div>
					</div>
					<div class="em_text__button-wrapper">
						<button class="el-gbtn em-text__popup-cancel-btn" @click="confirmClose()" >Cancel</button>
						<button class="el-btn" @click="closeAndSaveEditor()">Save</button>
					</div>
				</div>
				<div id="el-editor"></div>
			</div>
		</Popup>
		<ConfirmPopup
			:name="eventName"
			:buttons="confirmCloseButtons"
			:message="closeMessage"
			@confirm="onConfirmClose"
			@cancel="onCancelClose"
		/>
	</div>
</template>
<script>
	import EditorJS from '@editorjs/editorjs';
	import Table from '@editorjs/table';
	import Header from '@editorjs/header';
	import RawTool from '@editorjs/raw';
	import InlineCode from '@editorjs/inline-code';
	import Quote from '@editorjs/quote';
	import List from "@editorjs/list";
	import ImageTool from '@editorjs/image';

	export default
	{
		props: ['fieldValue','fieldSettings','mode', 'view'],
		components: {},
		data()
		{
			return {
				localValue: this.fieldValue,
				showEditor: false,
				editor: false,
				fieldCode: '',
				fieldName: '',
				closeMessage: this.$t('popups.confirmCloseButtons.close'),
				confirmCloseButtons: {
					confirm: {
						text: this.$t('popups.confirmCloseButtons.confirm_close'),
					},
					cancel: {
						text: this.$t('popups.confirmCloseButtons.cancel'),
					},
				}
			};
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
			},
			eventName()
			{
				return 'confirmCloseEdit' + this._uid;
			}
		},
		beforeDestroy()
		{
			this.closeEditor();
		},
		watch:
		{
			fieldValue:
			{
				handler(value)
				{
					if (!this.showEditor)
						this.$set(this, 'localValue', value);
				},
				deep: true,
			},
		},
		methods:
		{
			saveEditorContent()
			{
				this.editor.save().then((outputData) => {
					this.localValue = outputData;
					this.$emit('onChange', {
						value     : this.localValue,
						settings  : this.fieldSettings
					});
				})
			},
			getPopupTitle()
			{
				let columnSettings = this.$store.getters.getColumn(this.fieldSettings.tableCode, this.fieldSettings.fieldCode);
				this.fieldName = !!columnSettings.em.name ? columnSettings.em.name : columnSettings.em.settings.name;
				this.fieldCode = columnSettings.em.settings.name;
			},
			closeEditor()
			{
				this.showEditor = false;
			},
			closeAndSaveEditor()
			{
				this.saveEditorContent();
				this.closeEditor();
			},

			confirmClose()
			{
				this.$modal.show('confirmCloseEdit' + this._uid);
			},

			onConfirmClose()
			{
				this.$modal.hide('confirmCloseEdit' + this._uid);
				this.closeEditor();
			},
			onCancelClose()
			{
				this.$modal.hide('confirmCloseEdit' + this._uid);
			},

			openEditor()
			{
				this.showEditor = true;
				this.getPopupTitle();
				let data = this.localValue && typeof this.localValue == 'object'
					? this.localValue
					: {blocks: [{"type": "paragraph", "data": {"text": this.localValue } } ] };

				let self = this;
				this.editor = new EditorJS({
					logLevel: 'ERROR',
					holderId : 'el-editor',
					data     : data,
					tools:
					{
						quote: Quote,
						list:{
							class: List,
							inlineToolbar: true,
							config: {defaultStyle: 'unordered'}
						},
						raw: RawTool,
						inlineCode: InlineCode,
						header: Header,
						table:
						{
							class: Table,
							inlineToolbar: true,
							config: {rows: 2, cols: 3},
						},
						image:
						{
							class: ImageTool,
							config:
							{
								uploader:
								{
									async uploadByFile(file)
									{
										let formData   = new FormData();
										formData.append('file', file);
										let result = await self.$axios({
											method : 'POST',
											data   : formData,
											headers: { 'Content-Type': 'multipart/form-data' },
											url    : '/field/em_text/file/upload/'
										});
										if (!result.data.success)
											return false;

										return {
											success: 1,
											file: {url: result.data.path }
										}
									}
								}
							}
						}
					},
				});
			},
		},
	};
</script>
<style lang="scss">
	.em-text
	{
		min-width:500px;
		&.em-text--table
		{
			min-width:auto;
			position: absolute;
			width: 100%;
			left: 0px;
			top: 0px;
			height: 100%;
			display: flex;
			align-items: center;
			.em-text__txt{padding-left:10px; padding-right: 10px;}
		}
	}
	.em-text__txt
	{
		line-height: 17px;
		font-size: 12px;
		color: #677387;
		padding:11px 20px 11px 0;
		cursor: pointer;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}
	.em-text__popup .popup-block
	{
		width:800px;
		height:calc(100% - 144px);
	}
	.em-text__editor-wrapper
	{
		height: 100%;
		overflow: auto;
	}
	.em-text__popup-code
	{
		font-size: 10px;
		line-height: 12px;
		color: rgba(103, 115, 135, 0.4);
	}
	.em-text__popup-name
	{
		font-size: 16px;
		font-weight: 500;
		line-height: 19px;
		color: #191C21;
		margin-bottom: 3px;
	}
	.em-text__popup-head
	{
		display: flex;
		justify-content: space-between;
		margin-bottom: 40px;
		position: sticky;
		top: 0;
		z-index: 10;
		background: #fff;
	}
	.em-text__popup-cancel-btn
	{
		margin-right: 15px;
	}
	#el-editor
	{
		user-select: text;
		*{box-sizing: content-box; }
	}
	@media (max-width: 768px)
	{
		.em-text { min-width: unset; }
	}
	.em-text .ce-settings{left: 0;}
</style>
