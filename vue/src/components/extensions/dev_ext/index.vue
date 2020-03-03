<template>
	<div>
		Extension code {{url}}
		<DetailPopup
			:visible.sync="showDetail"
			tableCode="block_type"
			id="1"
		></DetailPopup>
		<button class="el-btn" @click="showDetail = true">show detail</button>
	</div>
</template>
<script>
	import DetailPopup from '@/components/popups/DetailPopup';
	export default
	{
		components:{DetailPopup},
		props: ['url'],
		data()
		{
			return {
				showDetail:false
			}
		}
	}
</script>