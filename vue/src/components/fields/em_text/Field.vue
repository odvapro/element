<template>
	<div class="em-text">
		<template v-if="view=='detail'">
			<vue-editor
				:editorOptions="editorSettings"
				:editorToolbar="toolbarOptions"
				v-model="localValue"
				@text-change="changeValue"
				placeholder="Empty"
			></vue-editor>
		</template>
		<template v-else>
			<input
				type="text"
				class="el-inp-noborder"
				@change="changeValue"
				:value="fieldValue"
				placeholder="Empty"
			/>
		</template>
	</div>
</template>
<script>
	import { VueEditor } from "vue2-editor";
	export default
	{
		props: ['fieldValue','fieldSettings','view'],
		components:{VueEditor},
		data()
		{
			return {
				localValue:this.fieldValue,
				editorSettings:
				{
					theme: 'bubble'
				},
				toolbarOptions:
				[
					[
						'bold', 'italic','link', 'image',
						{ 'list': 'ordered'}, { 'list': 'bullet' },
						{ 'header': [1, 2, 3, 4, 5, 6, false] }
					]
				]
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
					value    : this.localValue,
					settings : this.fieldSettings
				});
			}
		}
	}
</script>
<style lang="scss">
	@import url('http://cdn.quilljs.com/1.3.6/quill.bubble.css');
	.em-text
	{
		min-width:500px;
		.ql-editor
		{
			padding:10px;
			background: rgba(103, 115, 135, 0.05);
			border-radius: 2px;
			color: rgba(25, 28, 33, 0.7);
			font-family: $mainFont;
			font-size: 12px;
			line-height: 12px;
			overflow:visible;
		}
		.ql-tooltip{
			background: #191C21;
			border-radius: 4px;
		}
		.ql-bubble  .ql-tooltip .ql-tooltip-arrow{border-bottom:6px solid #191C21;}
		.ql-editor.ql-blank::before
		{
			font-style: normal;
			font-weight: normal;
			font-size: 10px;
			line-height: 12px;
			color: rgba(25, 28, 33, 0.4);
			left:10px;
		}
	}
</style>