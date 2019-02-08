<template>
	<div class="em-tags-wrapper">
		<TagItem :name="fieldValue.value"/>
	</div>
</template>
<script>
	import TagItem from '@/components/forms/TagItem.vue';
	export default
	{
		components: {TagItem},
		props: ['fieldValue']
	}
</script>