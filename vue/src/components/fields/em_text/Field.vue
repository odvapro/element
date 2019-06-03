<template>
	<div class="em-text">
		<template v-if="view=='detail'">
			<vue-editor
				:editorOptions="editorSettings"
				:editorToolbar="toolbarOptions"
				v-model="localValue"
			></vue-editor>
		</template>
		<!-- <div class="em-text__item-overide-name">{{ fieldValue }}</div> -->
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
				toolbarOptions:[
					['bold', 'italic'], ['link', 'image']
				]
			}
		}
	}
</script>
<style lang="scss">
	@import url('http://cdn.quilljs.com/1.3.6/quill.bubble.css');
	.em-text
	{
		.ql-editor{
			padding:0px;
		}
	}
</style>